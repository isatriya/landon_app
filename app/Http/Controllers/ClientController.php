<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Title as Title;
use App\Client as Client;

class ClientController extends Controller
{
    public function __construct(Title $titles, Client $client)
    {
        $this->titles = $titles->all();
        $this->client = $client;
    }

    public function di()
    {
        dd($this->titles);
    }

    public function index()
    {
        $data = [];

        // Hardcoded data as below.
        // $obj = new \stdClass;
        // $obj->id = 1;
        // $obj->title = 'mr';
        // $obj->name = 'john';
        // $obj->last_name = 'doe';
        // $obj->email = 'john@domain.com';
        // $data['clients'][] = $obj;

        // $obj = new \stdClass;
        // $obj->id = 2;
        // $obj->title = 'ms';
        // $obj->name = 'jane';
        // $obj->last_name = 'doe';
        // $obj->email = 'jane@another-domain.com';
        // $data['clients'][] = $obj;

        // Data from db.
        $data['clients'] = $this->client->all();

        return view('client/index', $data);
    }

    public function newClient(Request $request, Client $client)
    {
        $data = [];

        // set $data with data from $request.
        $data['title'] = $request->input('title');
        $data['name'] = $request->input('name');
        $data['last_name'] = $request->input('last_name');
        $data['address'] = $request->input('address');
        $data['zip_code'] = $request->input('zip_code');
        $data['city'] = $request->input('city');
        $data['state'] = $request->input('state');
        $data['email'] = $request->input('email');

        // Check if it is a POST request.
        if ($request->isMethod('post')) 
        {
            // Validate the $request.
            $this->validate(
                $request,
                [
                    'name' => 'required|min:5',
                    'last_name' => 'required',
                    'address' => 'required',
                    'zip_code' => 'required',
                    'city' => 'required',
                    'state' => 'required',
                    'email' => 'required',
                ]
            );

            // Do $data insertion to db.
            $client->insert($data);

            return redirect('clients');
        }

        // Set titles dropdown list items.
        $data['titles'] = $this->titles;

        // Set a flag to indicate it is on create new mode.
        $data['modify'] = 0;

        return view('client/form', $data);
    }

    public function create()
    {
        return view('client/create');
    }

    public function show($client_id, Request $request)
    {
        $data = [];

        // Set the $data['client_id'] so it is accessible on the view.
        $data['client_id'] = $client_id;

        // Set titles dropdown list items.
        $data['titles'] = $this->titles;

        // Set a flag to indicate it is on edit/ modify mode.
        $data['modify'] = 1;

        // Find client by $client_id.
        $client_data = $this->client->find($client_id);

        // Set $data from $client_data.
        $data['name'] = $client_data->name;
        $data['last_name'] = $client_data->last_name;
        $data['title'] = $client_data->title;
        $data['address'] = $client_data->address;
        $data['zip_code'] = $client_data->zip_code;
        $data['city'] = $client_data->city;
        $data['state'] = $client_data->state;
        $data['email'] = $client_data->email;

        // Store last_updated to session.
        $request->session()->put('last_updated', $client_data->name . '' , $client_data->last_name);

        return view('client/form', $data);
    }

    public function modify(Request $request, $client_id, Client $client)
    {
        $data = [];

        // set $data with data from $request.
        $data['title'] = $request->input('title');
        $data['name'] = $request->input('name');
        $data['last_name'] = $request->input('last_name');
        $data['address'] = $request->input('address');
        $data['zip_code'] = $request->input('zip_code');
        $data['city'] = $request->input('city');
        $data['state'] = $request->input('state');
        $data['email'] = $request->input('email');

        // Check if it is a POST request.
        if ($request->isMethod('post')) 
        {
            // Validate the $request.
            $this->validate(
                $request,
                [
                    'name' => 'required|min:5',
                    'last_name' => 'required',
                    'address' => 'required',
                    'zip_code' => 'required',
                    'city' => 'required',
                    'state' => 'required',
                    'email' => 'required',
                ]
            );

            // Find client by $client_id.
            $client_data = $this->client->find($client_id);

            $client_data->title = $request->input('title');
            $client_data->name = $request->input('name');
            $client_data->last_name = $request->input('last_name');
            $client_data->address = $request->input('address');
            $client_data->zip_code = $request->input('zip_code');
            $client_data->city = $request->input('city');
            $client_data->state = $request->input('state');
            $client_data->email = $request->input('email');

            $client_data->save();

            return redirect('clients');
        }

        // Set titles dropdown list items.
        $data['titles'] = $this->titles;

        // Set a flag to indicate it is on create new mode.
        $data['modify'] = 0;

        return view('client/form', $data);
    }

    public function export()
    {
        $data = [];

        // Data from db.
        $data['clients'] = $this->client->all();

        // Set the header Content-Disposition as attachment 
        // to indicate that it should force the user to download export.xls file
        // containg the clients list.
        header('Content-Disposition: attachment;filename=export.xls');

        return view('client/export', $data);
    }
}
