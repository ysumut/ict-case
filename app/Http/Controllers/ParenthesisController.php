<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;

class ParenthesisController extends Controller
{
    public function index(Request $request, string $data): \Illuminate\Http\JsonResponse
    {
        try {
            return response()->json($this->isValid($data));
        } catch (Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    /**
     * @throws Exception
     */
    private function isValid(string $data)
    {
        $allChars = str_split('(){}[]');
        $charEquivalent = [
            ')' => '(',
            '}' => '{',
            ']' => '[',
        ];
        $stack = [];
        $splitData = str_split(preg_replace('/\s+/', '', $data));
        foreach ($splitData as $char) {
            if (!in_array($char, $allChars)) {
                throw new Exception('Desteklenen parantezler: (), {}, []');
            }
            switch ($char) {
                case '(':
                case '{':
                case '[':
                    $stack[] = $char;
                    break;
                case ')' || '}' || ']':
                    if (end($stack) != $charEquivalent[$char]) {
                        return false;
                    }
                    array_pop($stack);
                    break;
            }
        }
        return empty($stack);
    }
}
