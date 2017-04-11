<?php

namespace App\Http\Controllers\Admin;

use App\Model\Notes;
use Illuminate\Http\Request;
use Flash;
use Auth;

class NotesController extends AdminController
{
    /**
     * @var notes
     */
    protected $notes;

    /**
     * Create a new notes controller instance.
     *
     * @return void
     */
    public function __construct(Request $request, Notes $notes)
    {
        parent::__construct($request);

        $this->middleware(function ($request, $next) {

            if (session('isAdmin') == false && session('isManager') == false){
                return redirect(route('admin.dashboard'));
            }

            return $next($request);
        });

        $this->notes = $notes;
        $this->page_title = 'Poznámky';
        $this->page_icon = 'fa fa-sticky-note';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Notes $notes)
    {
        $period_notes = $notes->getAllPeriod(session('selectedCompany'))->get();
        $sales_notes = $notes->getAllSale(session('selectedCompany'))->get();

        return view('admin.notes.index', compact('period_notes', 'sales_notes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->page_description = 'vytvoriť';

        //here can be created only period notes
        $type = 1;

        return view('admin.notes.create', compact('type'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id=null)
    {
        //set if sale note or period note
        if (isset($request['from']) && isset($request['to']) && $id==null){
            $from = $request['from'];
            $to = $request['to'];
            $type = 1;
        }else{
            $from = null;
            $to = null;
            $type = 0;
        }

        $store = $this->notes->create([
            'sale_id' => $id,
            'company_id' => session('selectedCompany'),
            'user_id' => Auth::user()->id,
            'note' => $request['note'],
            'from' => $from,
            'to' => $to,
            'type' => $type
        ]);

        Flash::success('Poznámka bola úspešne zapísaná!');

        if (isset($request['backto']) && $request['backto'] == 1){
            return redirect(route('admin.sales.index'));
        }
        elseif ($id == null){
            return redirect(route('admin.notes.index'));
        }else{
            return redirect(route('admin.sales.detail', $id));
        }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->page_description = 'upraviť';

        $notes = $this->notes->findOrFail($id);

        $edit = 1;

        return view('admin.notes.edit', compact('notes', 'edit'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validate = [
            'note' => 'required',
        ];

        $this->validate($request, $validate);
        $notes = $this->notes->findOrFail($id);

        $notes->update($request->all());


        Flash::success('Poznámka bola úspešne upravená!');
        return redirect(route('admin.notes.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        $products = $this->notes->findOrFail($id);
        $products->delete();

        Flash::success('Poznámka bola úspešne odstránená!');

        return redirect()->back();
    }
}
