<?php

namespace App\Http\Controllers\Registered;

use App\Apartment;
use App\Feature;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ApartmentController extends Controller
{
    private $validateRules;

    public function __construct()
    {
        $this->validateRules = [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'rooms_number' => 'required|integer',
            'beds_number' => 'required|integer',
            'bathrooms_number' => 'required|integer',
            'size' => 'required|integer',
            'address' => 'required|string',
            'latitude' => 'required|string',
            'longitude' => 'required|string',
            'featured_image' => 'required|image',
            'features' => 'required',
            'price' => 'integer',
            'visible' => 'required|boolean'
        ];
    }


    // //////////////////////////////////////////////////
    // I N D E X
    // //////////////////////////////////////////////////

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $apartments = Apartment::where('user_id', Auth::id())->get();
        return view('registered.apartments.index', compact('apartments'));
    }

    // //////////////////////////////////////////////////
    // C R E A T E
    // //////////////////////////////////////////////////

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = [
            'features'=>Feature::all(),
        ];
        return view('registered.apartments.create', $data);
    }

    // //////////////////////////////////////////////////
    // S T O R E
    // //////////////////////////////////////////////////

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $data = $request->all();
        $request->validate($this->validateRules);
        
        $newApartment = new Apartment;
        $newApartment->fill($data);
        $newApartment->user_id = Auth::id();
        
        if (!empty($data['featured_image'])) {
            $path = Storage::disk('public')->put('images', $data['featured_image']);
            $newApartment->featured_image = $path;
        }

        
        $saved = $newApartment->save();
        
        $newApartment->features()->attach($data['features']);
        
        if (!$saved) {
            return redirect()->back()->with('error', 'Errore durante l\'inserimento dell\'appartamento');
        }
        if (!empty($data['features'])){
            $newApartment->features()->attach($data['features']);
        }
        return redirect()->route('registered.apartments.show', $newApartment)->with('message', 'Appartamento inserito correttamente');

    }

    // //////////////////////////////////////////////////
    // S H O W
    // //////////////////////////////////////////////////

    /**
     * Display the specified resource.
     *
     * @param  \App\Apartment  $apartment
     * @return \Illuminate\Http\Response
     */
    public function show(Apartment $apartment)
    {
        return view('registered.apartments.show', compact('apartment'));
    }

    // //////////////////////////////////////////////////
    // E D I T
    // //////////////////////////////////////////////////

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Apartment  $apartment
     * @return \Illuminate\Http\Response
     */
    public function edit(Apartment $apartment)
    {
        $data = [
            'features' => Feature::all(),
            'apartment' => $apartment,
        ];
        return view('registered.apartments.edit', $data);
    }

    // //////////////////////////////////////////////////
    // U P D A T E
    // //////////////////////////////////////////////////

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Apartment  $apartment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Apartment $apartment)
    {
        
        $data = $request->all();
        $request->validate($this->validateRules);

        $apartment->fill($data);
        $apartment->user_id = Auth::id();

        if (!empty($data['featured_image'])) {
            $path = Storage::disk('public')->put('images', $data['featured_image']);
            $apartment->featured_image = $path;
        }

        
        $saved = $apartment->update();
        
        $apartment->features()->sync($data['features']);

        if (!$saved) {
            return redirect()->back()->with('error', 'Errore durante l\'aggiornamento dell\'appartamento');;
        }
        if (!empty($data['features'])) {
            $apartment->features()->attach($data['features']);
        }
    
        return redirect()->route('registered.apartments.show', $apartment)->with('message', 'Appartamento aggiornato correttamente');
    }

    // //////////////////////////////////////////////////
    // D E S T R O Y
    // //////////////////////////////////////////////////

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Apartment  $apartment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Apartment $apartment)
    {
        if (empty($apartment)) {
            abort(404);
        }
        $apartment->features()->detach();
        $apartment->delete();
        //no error only for color
        return redirect()->route('registered.apartments.index')->with('error', 'Appartamento cancellato correttamente');
    }
}
