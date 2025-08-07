<?php
namespace App\Http\Resources\Assignment;
use Illuminate\Http\Resources\Json\JsonResource;
class AssignmentResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => (int) $this->id,
            'title' => $this->title,
            'description' => $this->description,

            'dueDate' => $this->due_date ? $this->due_date->toDateTimeString() : null,
            'attachment' => $this->attachment,

            'teacherID' => (int) $this->teacher_id,
            'teacherName' => optional($this->teacher)->first_name. ' ' . optional($this->teacher)->last_name,

            'subjectId' => (int) $this->subject_id,
            'subjectName' => optional($this->subject)->name,

            'classID' => (int) $this->class_id,
            'className' => optional($this->class)->class_name,

            'sectionId' => (int) $this->section_id,
            'sectionName' => optional($this->section)->section_name,
        ];
    }
}
