<?php

namespace App\Repositories\Exam;

use App\Http\Facades\Firestore\FirestoreRepository;
use LaravelEasyRepository\Implementations\Eloquent;
use App\Models\Exam;

class ExamRepositoryImplement extends FirestoreRepository implements ExamRepository
{
}
