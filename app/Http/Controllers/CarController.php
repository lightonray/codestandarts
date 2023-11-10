<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Car;
use App\Http\Controllers\Auth;
use Illuminate\Support\Facades\Validator;

class CarController extends Controller
{
    /**
     * The car model instance.
     *
     * @var Car
     */
    private $car;

    /**
     * Create a new controller instance.
     *
     * @param  Car  $car
     */
    public function __construct(Car $car)
    {
        $this->car = $car;
    }

    /**
     * Display a listing of cars.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $cars = $this->car->with('user')->orderBy('id', 'desc')->paginate(10);

        return view('cars.dashboard', ['cars' => $cars]);
    }

    /**
     * Show the form for creating a new car.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('cars.create');
    }

    /**
     * Store a newly created car in storage.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        
        $this->validateCar($request);

        
        $car = $this->createCar($request);

        return redirect()->route('admin.car.index')->with('success', 'Car created successfully');
    }

    /**
     * Show the form for editing the specified car.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        
        $car = $this->car->find($id);

        return view('cars.edit', ['car' => $car]);
    }

    /**
     * Update the specified car in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        
        $this->validateCar($request);

        
        $car = $this->car->find($id);
        $car->update($request->all());

        return redirect()->route('admin.car.index')->with('success', 'Car was updated successfully');
    }

    /**
     * Remove the specified car from storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, $id)
    {
       
        $car = $this->car->find($id);
        $car->delete();

        return redirect()->route('admin.car.index')->with('success', 'Car was deleted successfully');
    }

    /**
     * Validate the car creation/update request.
     *
     * @param  Request  $request
     * @return void
     */
    private function validateCar(Request $request)
    {
        
        $this->validate($request, [
            'make' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'year' => 'required|integer',
        ]);
    }

    /**
     * Create a new car instance and store it in the database.
     *
     * @param  Request  $request
     * @return Car
     */
    private function createCar(Request $request)
    {
        
        $car = new Car([
            'make' => $request->input('make'),
            'model' => $request->input('model'),
            'year' => $request->input('year'),
        ]);

        
        $car->user_id = auth()->user()->id;

       
        $car->save();

        return $car;
    }
}
