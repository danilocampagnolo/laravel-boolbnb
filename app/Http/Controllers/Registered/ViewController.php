<?php

namespace App\Http\Controllers\Registered;

use App\Apartment;
use App\Charts\ViewChart;
use App\Http\Controllers\Controller;
use App\View;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ViewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\View  $view
     * @return \Illuminate\Http\Response
     */
    public function show(Apartment $apartment)
    {
      $views= DB::table('views')
        ->where('apartment_id', $apartment->id)
        ->whereDate('date', '>', today()->subMonth(2))
        ->groupBy('date')
        ->select(DB::raw('count(*) as views, date'))
        ->get();
      if(count($views) > 0){
        for ($i=0; $i < count($views); $i++) {
          $labels[] = Carbon::createFromDate($views[$i]->date)->format('d-M-Y') ;
          $dataset[] = $views[$i]->views;
        };
        $chart = new ViewChart;
        $chart->title('Visualizzazioni degli ultimi due mesi dell\'appartamento: '.$apartment->title, 20, '#666', true, "'Helvetica Neue', 'Helvetica', 'Arial', sans-serif");
        $chart->labels($labels);
        $chart->dataset('Visualizzazioni giornaliere', 'line', $dataset)->options([
          'backgroundColor' => 'rgba(20,109,112, .4)',
        ]);
        return view('registered.apartments.views.show', compact('chart'));
      }
      return view('registered.apartments.views.show');

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\View  $view
     * @return \Illuminate\Http\Response
     */
    public function edit(View $view)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\View  $view
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, View $view)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\View  $view
     * @return \Illuminate\Http\Response
     */
    public function destroy(View $view)
    {
        //
    }
}
