<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Post;
// use App\Exports\ListExport;
use App\Exports;




class PostsController extends Controller
{
  /**
   * Get list of articles
   */
  public function index()
  {
    $list = Post::get();
    return view('list',compact('list'));
  }
  /**
   * Export to excel
   */
  public function exportExcel()
  {
    return Excel::download(new ListExport, 'list.xlsx');
  }
  /**
   * Export to csv
   */
  public function exportCSV()
  {
    return Excel::download(new ListExport, 'list.csv');
  }
}