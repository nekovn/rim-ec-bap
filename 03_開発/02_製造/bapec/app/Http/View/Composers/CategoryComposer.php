<?php

namespace App\Http\View\Composers;

use Illuminate\Contracts\View\View;
use App\Repositories\CategoriesRepository;

/**
 * カテゴリを表示するComposer設定
 */
class CategoryComposer {
   
   protected $categories;

   public function __construct(CategoriesRepository $categoriesRepository) {
        $this->categories = $categoriesRepository->getCategoryList();
   }
   
   public function compose(View $view) {
        $view->with('categoryClass1', $this->categories['class1']);
        $view->with('categoryClass2', $this->categories['class2']);
   }
}