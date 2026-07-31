<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ChatbotSettingsRequest;
use App\Models\ChatbotSetting;

class ChatbotSettingsController extends Controller
{
    public function edit()
    {
        return view('admin.chatbot.settings', ['settings' => ChatbotSetting::current()]);
    }

    public function update(ChatbotSettingsRequest $request)
    {
        ChatbotSetting::current()->update($request->validated());

        return back()->with('success', 'Chatbot settings updated.');
    }
}
