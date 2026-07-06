<?php


namespace Modules\Common\Repositories;

use Modules\Common\Entities\Notice;
use Modules\Common\Repositories\NoticeRepositoryInterface;

class NoticeRepository implements NoticeRepositoryInterface
{
    protected $model;

    public function __construct(Notice $model)
    {
        $this->model = $model;
    }

    public function all()
    {
        return $this->model
            ->orderBy('sort_order', 'asc')
            ->latest()
            ->get();
    }

    public function paginate($perPage = 15)
    {
        return $this->model
            ->orderBy('sort_order', 'asc')
            ->latest() // optional (for tie-breaking)
            ->paginate($perPage);
    }

    public function find($id)
    {
        return $this->model->findOrFail($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update($id, array $data)
    {
        $notice = $this->find($id);
        $notice->update($data);
        return $notice;
    }

    public function delete($id)
    {
        $notice = $this->find($id);
        return $notice->delete();
    }

    public function findBySlug($slug)
    {
        return $this->model->where('slug', $slug)->firstOrFail();
    }

    public function toggleStatus($id)
    {
        $notice = $this->find($id);
        $notice->status = !$notice->status;
        $notice->save();
        return $notice;
    }

    public function toggleFeatured($id)
    {
        $notice = $this->find($id);
        $notice->is_featured = !$notice->is_featured;
        $notice->save();
        return $notice;
    }

    public function search($keyword, $perPage = 15)
    {
        return $this->model
            ->where('title', 'like', "%{$keyword}%")
            ->orWhere('content', 'like', "%{$keyword}%")
            ->orderBy('sort_order', 'asc')
            ->latest()
            ->paginate($perPage);
    }

    public function getStatistics()
    {
        return [
            'total' => $this->model->count(),
            'active' => $this->model->where('status', true)->count(),
            'featured' => $this->model->where('is_featured', true)->count(),
            'news' => $this->model->where('type', 'news')->count(),
            'notices' => $this->model->where('type', 'notice')->count(),
            'total_views' => $this->model->sum('views'),
        ];
    }
}
