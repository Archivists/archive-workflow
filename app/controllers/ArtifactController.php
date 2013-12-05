<?php
/*
|--------------------------------------------------------------------------
| Artifact Controller
|--------------------------------------------------------------------------
|
| See routes.php  ->
| Route::resource('artifacts', 'ArtifactController');
| Route::get('artifacts/{artifacts}/delete', 'ArtifactController@delete');
|
*/

class ArtifactController extends BaseController
{
    /**
     * Artifact Model
     * @var Artifact
     */
    protected $artifact;

    /**
     * Parent Carrier Model
     * @var Carrier
     */
    protected $carrier;

    /**
     * Inject the models.
     * @param Artifact $artifact
     * @param Carrier $carrier
     */
    public function __construct(Artifact $artifact, Carrier $carrier)
    {
        parent::__construct();
        $this->artifact = $artifact;
        $this->carrier = $carrier;
    }

    /**
     * Display a listing of the resource.
     *
     * See public function data() below for the data source for the list,
     * and the view/artifact/index.blade.php for the jQuery script that makes
     * the Ajax request.
     *
     * @return Response
     */
    public function index($carrier_id)
    {   
        $carrier = $this->carrier->find($carrier_id);

        if ($carrier->id) {
            // Title
            $title = Lang::get('artifact/title.artifact_management');

            // Show the page
            return View::make('artifact/index', compact('carrier', 'title'));

        } else {
            // Redirect to the carrier management page
            return Redirect::to('carriers')->with('error', Lang::get('carrier/messages.does_not_exist'));
        }
    }

    /**
     * Show a single artifact details page.
     *
     * @return View
     */
    public function show($carrier_id, $id)
    {
        $artifact = $this->artifact->where('id','=',$id)->where('carrier_id', '=', $carrier_id)->first();

        if ($artifact->id) {
            // Title
            $title = Lang::get('artifact/title.artifact_show');

            // Show the page
            return View::make('artifact/show', compact('carrier_id', 'artifact', 'title'));

        } else {
            // Redirect to the artifact management page
            return Redirect::to('carriers/' . $carrier_id. '/artifacts')->with('error', Lang::get('artifact/messages.does_not_exist'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create($carrier_id)
    {
        // Title
        $title = Lang::get('artifact/title.create_a_new_artifact');

        // Show the page
        return View::make('artifact/create', compact('carrier_id', 'title'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store($carrier_id)
    {
        // Validate the inputs
        $rules = array(
            'name'=> 'required|alpha_dash|unique:artifacts,name'
            );

        // Validate the inputs
        $validator = Validator::make(Input::all(), $rules);

        // Check if the form validates with success
        if ($validator->passes()) {
            
            // Get the inputs, with some exceptions
            $inputs = Input::except('csrf_token');

            $this->artifact->carrier_id = $carrier_id;
            $this->artifact->name = $inputs['name'];
            //$this->artifact->description = $inputs['description'];

            if ($this->artifact->save($rules)) {
                // Redirect to the new artifact page
                return Redirect::to('carriers/' . $carrier_id. '/artifacts')->with('success', Lang::get('artifact/messages.create.success'));

            } else {
                // Redirect to the artifact create page
                //var_dump($this->artifact);
                return Redirect::to('carriers/' . $carrier_id. '/artifacts/create')->with('error', Lang::get('artifact/messages.create.error'));
            }
        } else {
            // Form validation failed
            return Redirect::to('carriers/' . $carrier_id. '/artifacts/create')->withInput()->withErrors($validator);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $artifact
     * @return Response
     */
    public function edit($carrier_id, $id)
    {
        $artifact = $this->artifact->where('id', '=', $id)->where('carrier_id', '=', $carrier_id)->first();;

        if ($artifact) {

        } else {
            // Redirect to the artifact management page
            return Redirect::to('carriers/' . $carrier_id. '/artifacts')->with('error', Lang::get('artifact/messages.does_not_exist'));
        }

        // Title
        $title = Lang::get('artifact/title.artifact_update');

        // Show the page
        return View::make('artifact/edit', compact('carrier_id', 'artifact', 'title'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param $artifact
     * @return Response
     */
    public function update($carrier_id, $id)
    {
        $artifact = $this->artifact->where('id', '=', $id)->where('carrier_id', '=', $carrier_id)->first();;

        $rules = array(
                'name'=> 'required|alpha_dash|unique:artifacts,name,' . $artifact->id
            );

        // Validate the inputs
        $validator = Validator::make(Input::all(), $rules);

        // Check if the form validates with success
        if ($validator->passes()) {

            // Get the inputs, with some exceptions
            $inputs = Input::except('csrf_token');

            $artifact->name = $inputs['name'];
            $artifact->carrier_id = $carrier_id;
            //$artifact->description = $inputs['description'];

            // Was the artifact updated?
            if ($artifact->save($rules)) {
                // Redirect to the artifact page
                return Redirect::to('carriers/' . $carrier_id. '/artifacts/' . $artifact->id . '/edit')->with('success', Lang::get('artifact/messages.update.success'));
            } else {
                // Redirect to the artifact page
                return Redirect::to('carriers/' . $carrier_id. '/artifacts/' . $artifact->id . '/edit')->with('error', Lang::get('artifact/messages.update.error'));
            }
        } else {
            // Form validation failed
            return Redirect::to('carriers/' . $carrier_id. '/artifacts/' . $artifact->id . '/edit')->withInput()->withErrors($validator);
        }
    }

    /**
     * Remove artifact page.
     *
     * @param $artifact
     * @return Response
     */
    public function delete($carrier_id, $id)
    {
        $artifact = $this->artifact->where('id', '=', $id)->where('carrier_id', '=', $carrier_id)->first();;

        // Title
        $title = Lang::get('artifact/title.artifact_delete');

        if ($artifact) {

        } else {
            // Redirect to the artifact management page
            return Redirect::to('carriers/' . $carrier_id. '/artifacts')->with('error', Lang::get('artifact/messages.does_not_exist'));
        }

        // Show the record
        return View::make('artifact/delete', compact('carrier_id', 'artifact', 'title'));
    }

    /**
     * Remove the specified artifact from storage.
     * @internal param $id
     * @return Response
     */
    public function destroy($carrier_id, $id)
    {
        $artifact = $this->artifact->where('id', '=', $id)->where('carrier_id', '=', $carrier_id)->first();;

        // Was the artifact deleted?
        if ($artifact->delete()) {
            // Redirect to the artifact management page
            return Redirect::to('carriers/' . $carrier_id. '/artifacts')->with('success', Lang::get('artifact/messages.delete.success'));
        }

        // There was a problem deleting the artifact
        return Redirect::to('carriers/' . $carrier_id. '/artifacts')->with('error', Lang::get('artifact/messages.delete.error'));
    }

    /**
     * Show a list of all the artifacts formatted for Datatables.
     *
     * @return Datatables JSON
     */
    public function data($carrier_id)
    {
        //Make this method testable and mockable by using our injected $artifact member.
        $artifacts = $this->artifact->select(array('artifacts.id',  'artifacts.name', 'artifacts.created_at'))->where('carrier_id', '=', $carrier_id);

        return Datatables::of($artifacts)
        // ->edit_column('created_at','{{{ Carbon::now()->diffForHumans(Carbon::createFromFormat(\'Y-m-d H\', $test)) }}}')

        ->add_column('actions', '<div class="btn-group">
                  <button type="button" class="btn btn-xs btn-primary dropdown-toggle" data-toggle="dropdown">
                    Action <span class="caret"></span>
                  </button>
                  <ul class="dropdown-menu" role="menu">
                    <li><a href="{{{ URL::to(\'carriers/' . $carrier_id. '/artifacts/\' . $id ) }}}">{{{ Lang::get(\'button.show\') }}}</a></li>
                    <li><a href="{{{ URL::to(\'carriers/' . $carrier_id. '/artifacts/\' . $id . \'/edit\' ) }}}">{{{ Lang::get(\'button.edit\') }}}</a></li>
                    <li><a href="{{{ URL::to(\'carriers/' . $carrier_id. '/artifacts/\' . $id . \'/delete\' ) }}}">{{{ Lang::get(\'button.delete\') }}}</a></li>
                  </ul>
                </div>')

        ->remove_column('id')

        ->make();
    }
}
