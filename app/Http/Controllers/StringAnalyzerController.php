<?php

namespace App\Http\Controllers;

use App\Models\StringAnalyzer;
use App\Services\StringAnalyzerService;
use Illuminate\Http\Request;
use Illuminate\JsonSchema\Types\BooleanType;
use Illuminate\Support\Facades\Validator;

class StringAnalyzerController extends Controller
{
    public function index(Request $request)
    {

        // get all filtered data from request
        // $data = $request->validate([
        //     'is_palindrome' => ['sometimes', 'boolean'],
        //     'min_length' => ['sometimes', 'integer', 'min:1'],
        //     'max_length' => ['sometimes', 'integer', 'min:1'],
        //     'word_count' => ['sometimes', 'integer', 'min:1'],
        //     'contains_character' => ['sometimes', 'string', 'max:1'],
        // ]);

        // custom validation to return all errors
        $validate = Validator::make($request->all(), [
            'is_palindrome' => ['sometimes', 'in:true,false,1,0'],
            'min_length' => ['sometimes', 'integer', 'min:1'],
            'max_length' => ['sometimes', 'integer', 'min:1'],
            'word_count' => ['sometimes', 'integer', 'min:1'],
            'contains_character' => ['sometimes', 'string', 'max:1'],
        ]);

        // if validation fails custom error response
        if ($validate->fails()) {
            return response()->json([
                'message' => $validate->messages()->first(),
                'errors' => $validate->errors(),
            ], 422);
        }

        // get validated data
        $data = $validate->validated();


        // Get all string analyzers from the database
        $stringAnalyzers = StringAnalyzer::query();

        // GET /strings?is_palindrome=true&min_length=5&max_length=20&word_count=2&contains_character=a
        $stringAnalyzers->when((!empty($data['is_palindrome']) && (bool) $data['is_palindrome'] == true), function ($query) {
            $query->where('is_palindrome', true);
        });

        $stringAnalyzers->when(isset($data['min_length']), function ($query) use ($data) {
            $query->where('length', '>=', $data['min_length']);
        });

        $stringAnalyzers->when(isset($data['max_length']), function ($query) use ($data) {
            $query->where('length', '<=', $data['max_length']);
        });

        $stringAnalyzers->when(isset($data['word_count']), function ($query) use ($data) {
            $query->where('word_count', $data['word_count']);
        });

        $stringAnalyzers->when(isset($data['contains_character']), function ($query) use ($data) {
            $query->where('value', 'LIKE', '%' . $data['contains_character'] . '%');
        });

        $stringAnalyzers = $stringAnalyzers->get(['id', 'value', 'is_palindrome', 'word_count', 'created_at']);

        // using map (modifies the collection in place):
        $stringAnalyzerService = new StringAnalyzerService();
        foreach ($stringAnalyzers as $stringAnalyzer) {
            $stringAnalyzer->property = $stringAnalyzerService->getProperties($stringAnalyzer);
        }


        // if not exists return empty data with count 0
        if ($stringAnalyzers->isEmpty()) {
            return response()->json([
                'data' => [],
                'count' => 0,
                'filters_applied' => $data,
            ], 404);
        }

        // {
        // "data": [
        //     {
        //     "id": "hash1",
        //     "value": "string1",
        //     "properties": { /* ... */ },
        //     "created_at": "2025-08-27T10:00:00Z"
        //     },
        //     // ... more strings
        // ],
        // "count": 15,
        // "filters_applied": {
        //     "is_palindrome": true,
        //     "min_length": 5,
        //     "max_length": 20,
        //     "word_count": 2,
        //     "contains_character": "a"
        // }
        // }

        return response()->json([
            'data' => $stringAnalyzers,
            'count' => $stringAnalyzers->count(),
            'filters_applied' => $data,
        ], 200);
    }

    // public function show($id, StringAnalyzerService $stringAnalyzerService)
    // {
    //     // Get a specific string analyzer by ID
    //     $stringAnalyzer = StringAnalyzer::findOrFail($id);
    //     $properties = $stringAnalyzerService->getProperties($stringAnalyzer);
    //     return response()->json([
    //         'id' => $stringAnalyzer->id,
    //         'value' => $stringAnalyzer->value,
    //         'properties' => $properties,
    //         'created_at' => $stringAnalyzer->created_at,
    //     ], 200);
    // }

    public function show($string, StringAnalyzerService $stringAnalyzerService)
    {
        // Natural Language Filtering
        if ($string == 'filter-by-natural-language') {
            return $this->naturalLanguageProcessing(request());
        }

        // Get a specific string analyzer by ID
        $stringAnalyzer = StringAnalyzer::where('value', $string)->firstOrFail();
        $properties = $stringAnalyzerService->getProperties($stringAnalyzer);
        return response()->json([
            'id' => $stringAnalyzer->id,
            'value' => $stringAnalyzer->value,
            'properties' => $properties,
            'created_at' => $stringAnalyzer->created_at,
        ], 200);
    }

    public function store(Request $request, StringAnalyzerService $stringAnalyzerService)
    {
        // Validate and store a new string analyzer
        $validated = $request->validate([
            'value' => 'required|string|unique:string_analyzers,value',
        ]);
        $stringAnalyzer = StringAnalyzerService::analyzeAndStore($validated['value']);
        $properties = $stringAnalyzerService->getProperties($stringAnalyzer);
        return response()->json([
            'id' => $stringAnalyzer->id,
            'value' => $stringAnalyzer->value,
            'properties' => $properties,
            'created_at' => $stringAnalyzer->created_at,
        ], 201);
    }

    public function destroy($string)
    {
        $string = StringAnalyzer::where('string', $string)->firstOrFail();
        // Delete a specific string analyzer
        $string->delete();
        return response()->json([], 204);
    }

    public function naturalLanguageProcessing(Request $request)
    {

        // Natural Language Filtering
        // GET /strings/filter-by-natural-language?query=all%20single%20word%20palindromic%20strings
        if (!$request->has('query')) {
            return response()->json([
                'message' => 'Query parameter is required.',
            ], 400);
        }

        $dbQuery = StringAnalyzer::query();
        // split query item into array
        $queryItems = explode(' ', $request->input('query'));
        foreach ($queryItems as $items){
            $dbQuery->orWhereAny(['id', 'value', 'length', 'word_count'], 'LIKE', '%' . $items . '%');    
        }
        // get all filtered data from request
        $filters = $this->checkFilters($request->query('query'));
        foreach ($filters as $key => $value) {
            $dbQuery->orWhere($key, $value);
        }
        // return $dbQuery->ddRawSql();
        // return $dbQuery->get();

        $stringAnalyzers = $dbQuery->get(['id', 'value', 'is_palindrome', 'word_count', 'created_at']);

        // using map (modifies the collection in place):
        $stringAnalyzerService = new StringAnalyzerService();
        foreach ($stringAnalyzers as $stringAnalyzer) {
            $stringAnalyzer->property = $stringAnalyzerService->getProperties($stringAnalyzer);
        }

        // if not exists return empty data with count 0
        if ($stringAnalyzers->isEmpty()) {
            return response()->json([
                'data' => [],
                'count' => 0,
                'filters_applied' => $request->query('query'),
            ], 404);
        }



        return response()->json([
            'data' => $stringAnalyzers,
            'count' => $stringAnalyzers->count(),
            'interpreted_query' => [
                'original' => $request->query('query'),
                'parsed_filters' => $filters,
            ]
        ], 200);
    }


    protected function checkFilters($query)
    {
        $filters = [];

        $query = strtolower($query);

        if (str_contains($query, 'palindrome')) {
            $filters['is_palindrome'] = true;
        }

        if (str_contains($query, 'single word') || str_contains($query, 'word count') || str_contains($query, 'count word')) {
            $filters['word_count'] = 1;
        }
        
        return $filters;
    }    
}
