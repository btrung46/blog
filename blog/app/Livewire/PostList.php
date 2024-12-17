<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Post;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class PostList extends Component
{
    use WithPagination;
    #[Url()]
    public $sort = 'desc';
    #[Url()]
    public $search = '';
    #[Url()]
    public $category = '';
    public function setSort($sort)
    {
        $this->sort = ($sort === 'desc') ? 'desc' : 'asc';
    }
    public function clearFilters()
    {
        $this->search = '';
        $this->category = '';
        $this->resetPage();
    }
    #[On('search')]
    public function updatedSearch($search) {
        $this->search = $search;
    }
    #[Computed()]
    public function posts(){
        return Post::published()
        ->when($this->activeCategory, function ($query) {
            $query->withCategory($this->category);
        })
        ->search($this->search) 
        ->orderBy("published_at",$this->sort)
        ->simplePaginate(3);
    }
    #[Computed()]
    public function activeCategory()
    {
        return Category::where('slug', $this->category)->first();
    }
    public function render()
    {
        return view('livewire.post-list');
    }
}
