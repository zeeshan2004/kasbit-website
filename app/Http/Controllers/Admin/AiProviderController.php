<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AiProviderRequest;
use App\Models\AiProvider;
use App\Models\ChatbotSetting;
use App\Services\Chatbot\AiProviderManager;
use Illuminate\Support\Facades\DB;

class AiProviderController extends Controller
{
    public function index()
    {
        return view('admin.chatbot.providers', ['providers' => AiProvider::orderBy('id')->get()]);
    }

    public function store(AiProviderRequest $request)
    {
        $provider = DB::transaction(function () use ($request) {
            $data = $request->validated();

            if ($data['is_default'] || ! AiProvider::where('is_default', true)->exists()) {
                AiProvider::query()->update(['is_default' => false]);
                $data['is_active'] = true;
                $data['is_default'] = true;
            }

            return AiProvider::create($data);
        });

        return redirect()->route('admin.chatbot.providers.index')
            ->with('success', "{$provider->name} provider added.");
    }

    public function update(AiProviderRequest $request, AiProvider $provider)
    {
        DB::transaction(function () use ($request, $provider) {
            $data = $request->validated();

            if ($data['is_default']) {
                AiProvider::where('id', '!=', $provider->id)->update(['is_default' => false]);
                $data['is_active'] = true;
            } elseif ($provider->is_default
                && ! AiProvider::where('id', '!=', $provider->id)->where('is_default', true)->exists()) {
                $data['is_default'] = true;
                $data['is_active'] = true;
            }
            $provider->update($data);
        });

        return back()->with('success', "{$provider->name} provider updated.");
    }

    public function destroy(AiProvider $provider)
    {
        DB::transaction(function () use ($provider) {
            $wasDefault = $provider->is_default;
            $provider->delete();

            if ($wasDefault && ($replacement = AiProvider::orderByDesc('is_active')->orderBy('id')->first())) {
                $replacement->update(['is_default' => true, 'is_active' => true]);
            }
        });

        return back()->with('success', 'Provider removed.');
    }

    public function makeDefault(AiProvider $provider)
    {
        DB::transaction(function () use ($provider) {
            AiProvider::query()->update(['is_default' => false]);
            $provider->update(['is_default' => true, 'is_active' => true]);
        });

        return back()->with('success', "{$provider->name} is now the default provider.");
    }

    public function test(AiProvider $provider, AiProviderManager $manager)
    {
        $settings = ChatbotSetting::current();
        $response = $manager->generate(
            $provider,
            'Reply with only: Connection successful.',
            $settings->system_prompt,
        );

        $provider->update([
            'last_tested_at' => now(),
            'last_test_status' => $response->successful ? 'success' : 'failed',
            'last_test_message' => $response->successful ? $response->answer : $response->error,
        ]);

        return back()->with(
            $response->successful ? 'success' : 'error',
            $response->successful ? 'Provider connection succeeded.' : "Provider test failed: {$response->error}",
        );
    }
}
