<?php

namespace App\Http\Controllers;

use App\Http\Requests\SendChatbotMessageRequest;
use App\Http\Requests\SaveChatbotProfileRequest;
use App\Models\ChatbotSetting;
use App\Models\Department;
use App\Services\Chatbot\ChatbotService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class ChatbotController extends Controller
{
    
    public function __construct(private readonly ChatbotService $chatbot)
    {
    }

    public function bootstrap(Request $request): JsonResponse
    {
        $settings = ChatbotSetting::current();
        $this->ensureAvailable($settings);

        return response()->json($this->chatbot->bootstrap($request, $settings));
    }

    public function message(SendChatbotMessageRequest $request): JsonResponse
    {
        $settings = ChatbotSetting::current();
        $this->ensureAvailable($settings);

        if (! $this->chatbot->profile($request)) {
            return response()->json([
                'message' => 'Please enter your student ID, full name and department before asking a question.',
            ], 422);
        }

        $message = (string) $request->validated('message');

        if (Str::length($message) > $settings->max_message_length) {
            return response()->json([
                'message' => "Please keep your question within {$settings->max_message_length} characters.",
                'errors' => ['message' => ['Your question is too long.']],
            ], 422);
        }

        $key = 'chatbot:'.($this->userId() ?: $request->ip());
        $limit = max(1, $settings->max_questions_per_minute);

        if (RateLimiter::tooManyAttempts($key, $limit)) {
            return response()->json([
                'message' => 'Too many questions. Please wait a moment and try again.',
                'retry_after' => RateLimiter::availableIn($key),
            ], 429);
        }

        RateLimiter::hit($key, 60);

        return response()->json($this->chatbot->answer($message, $request, $settings));
    }

    public function profile(SaveChatbotProfileRequest $request): JsonResponse
    {
        $settings = ChatbotSetting::current();
        $this->ensureAvailable($settings);

        $department = Department::active()->findOrFail($request->integer('department_id'));
        $profile = $this->chatbot->saveProfile($request, [
            'student_id' => $request->string('student_id')->toString(),
            'full_name' => $request->string('full_name')->toString(),
            'department_id' => $department->id,
            'department_name' => $department->name,
        ]);

        return response()->json([
            'message' => 'Details saved. You can now ask your question.',
            'profile' => $profile,
        ]);
    }

    public function clear(Request $request): JsonResponse
    {
        $this->chatbot->clear($request);

        return response()->json(['message' => 'Chat history cleared.']);
    }

    private function ensureAvailable(ChatbotSetting $settings): void
    {
        abort_unless($settings->is_enabled, 404);
        abort_if(! $settings->guest_chat_enabled && ! $this->userId(), 403, 'Please sign in to use the chatbot.');
    }

    private function userId(): ?int
    {
        return Auth::guard('student')->id() ?: Auth::guard('web')->id();
    }
}
