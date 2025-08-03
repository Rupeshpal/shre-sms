<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AssignmentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'title'       => $this->title,
            'description' => $this->description,
            'teacherId'   => $this->teacher_id,
            'subjectId'   => $this->subject_id,
            'sectionId'   => $this->section_id,
            'classId'     => $this->class_id,
            'dueDate'     => $this->due_date,
            'attachment'  => $this->attachment,
            'createdAt'   => $this->created_at,
            'updatedAt'   => $this->updated_at,
        ];
    }
}
