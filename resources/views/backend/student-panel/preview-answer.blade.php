<div class="card-body" id="answer">
    <style>
        .answer {
            border: 1px solid #ccc;
            padding: 0px 5px 2px 6px;
            border-radius: 50%;
            /* background: #bbbbbb;
        color: white; */
        }
    </style>

    <div class="container m-3 text-center">
        <div class="row">
            <div class="col-md-12">
                <h4>{{ $company->name }}</h4>
                <div>
                    <strong>Course: </strong>{{ $exam->course->name }},
                    <strong> Subject: </strong> {{ $exam->subject->name }},
                    <strong> Exam: </strong> {{ $exam->name }}
                </div>
                <div>
                    <strong>Total Question: </strong>{{ $exam->total_question }},
                    <strong> Marks: </strong>
                    {{ number_format($exam->per_question_positive_mark * $exam->total_question, 2) }}({{ $exam->per_question_positive_mark }}x{{ $exam->total_question }}),
                    <strong> Per Question Negative Mark: </strong> {{ $exam->per_question_negative_mark }}
                </div>
            </div>
        </div>
    </div>
    <hr>
    <ol>
        <div class="row">
            @foreach ($data as $item)
                <div class="col-md-2 mb-2">
                    {{ $loop->iteration }}. <span class="answer">
                        @foreach ($item->examQuestionOptions as $option)
                            @php
                                if ($loop->iteration > 4) {
                                    continue;
                                } else {
                                    if ($option->is_answer == 1) {
                                        echo $loop->iteration;
                                    }
                                }
                            @endphp
                        @endforeach
                    </span>
                </div>
            @endforeach
        </div>
    </ol>
    {{-- <ol>
        <div class="row">
            @foreach ($data as $item)
                <div class="col-md-6">
                    <li class="m-3">
                        {!! $item->question_name !!}
                        <ol>
                            @foreach ($item->examQuestionOptions as $option)
                                @php
                                    if ($loop->iteration > 4) {
                                        continue;
                                    }
                                @endphp
                                <li>{{ $option->option }}</li>
                            @endforeach
                        </ol>
                    </li>
                </div>
            @endforeach
        </div>
    </ol> --}}
</div>
