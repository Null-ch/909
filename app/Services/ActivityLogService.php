<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class ActivityLogService
{
    public function log(
        string $action,
        string $entityType,
        ?int $entityId,
        string $description,
        ?array $properties = null,
    ): ActivityLog {
        return ActivityLog::query()->create([
            'user_id' => Auth::id(),
            'action' => $action,
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'description' => $description,
            'properties' => $properties,
        ]);
    }

    /**
     * @return array{draw: int, recordsTotal: int, recordsFiltered: int, data: array<int, array<string, mixed>>}
     */
    public function datatable(\Illuminate\Http\Request $request): array
    {
        $draw = (int) $request->input('draw', 1);
        $start = (int) $request->input('start', 0);
        $length = (int) $request->input('length', 10);
        $search = trim((string) $request->input('search.value', ''));

        $query = ActivityLog::query()
            ->with('user')
            ->orderByDesc('created_at');

        $recordsTotal = ActivityLog::query()->count();

        if ($search !== '') {
            $query->where(function ($builder) use ($search) {
                $builder->where('description', 'like', "%{$search}%")
                    ->orWhere('entity_type', 'like', "%{$search}%")
                    ->orWhere('action', 'like', "%{$search}%")
                    ->orWhereHas('user', fn ($userQuery) => $userQuery
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%"));
            });
        }

        $recordsFiltered = (clone $query)->count();

        $rows = $query
            ->skip($start)
            ->take($length > 0 ? $length : 15)
            ->get();

        return [
            'draw' => $draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $rows->map(fn (ActivityLog $log) => [
                'id' => $log->id,
                'user' => e($log->user?->name ?? 'Система'),
                'action' => e($this->actionLabel($log->action)),
                'entity' => e($log->entity_type.($log->entity_id ? ' #'.$log->entity_id : '')),
                'description' => e($log->description),
                'created_at' => $log->created_at?->format('d.m.Y H:i') ?? '—',
            ])->all(),
        ];
    }

    private function actionLabel(string $action): string
    {
        return match ($action) {
            'created' => 'Создание',
            'updated' => 'Изменение',
            'deleted' => 'Удаление',
            default => $action,
        };
    }
}
