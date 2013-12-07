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
     * FileRepository Service
     * @var FileRepository
     */
    protected $fileRepo;

    /**
     * Inject the models.
     * @param Artifact $artifact
     * @param Carrier $carrier
     */
    public function __construct(Artifact $artifact, Carrier $carrier, FileRepository $fileRepo)
    {
        parent::__construct();
        $this->artifact = $artifact;
        $this->carrier = $carrier;
        $this->fileRepo = $fileRepo;
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
        $carrier = $this->carrier->find($carrier_id);

        if($carrier->id) {
            
            // Get the inputs, with some exceptions
            if (Input::hasFile('artifact')) {

                $uploaded = Input::file('artifact')->getRealPath();
                $count = $carrier->artifacts()->count() + 1;
                $name = $carrier->archive_id . '-' . str_pad($count, 2, '0', STR_PAD_LEFT) . '.' . strtolower(Input::file('artifact')->getClientOriginalExtension());

                if ($this->fileRepo->move_file($carrier->archive_id, $uploaded, $name)) {
                    
                    $this->artifact->name = $name;
                    $this->artifact->carrier_id = $carrier->id;
                    
                    if ($this->artifact->save()) {
                        return Redirect::to('carriers/' . $carrier_id)->with('success', Lang::get('artifact/messages.create.success'));
                    } else {
                        return Redirect::to('carriers/' . $carrier_id)->with('error', Lang::get('artifact/messages.create.error'));
                    } 

                } else {
                    return Redirect::to('carriers/' . $carrier_id)->with('error', 'There was a problem saving the artifact to the repository.');
                }

            } else {
                return Redirect::to('carriers/' . $carrier_id)->with('error', 'Please select a file to upload and associate as an artifact with this carrier.');                
            }

        } else {
            return Redirect::to('carriers')->with('error', 'Carrier not found.');
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
            return Redirect::to('carriers/' . $carrier_id)->with('success', Lang::get('artifact/messages.delete.success'));
        }

        // There was a problem deleting the artifact
        return Redirect::to('carriers/' . $carrier_id)->with('error', Lang::get('artifact/messages.delete.error'));
    }

    /**
     * Show a list of all the artifacts formatted for Datatables.
     *
     * @return Datatables JSON
     */
    public function data($carrier_id)
    {
        // We need the archive_id for links to thumbnals or default icons, and so 
        // therefore need to retrieve the complete carrier object here.
        $carrier = $this->carrier->find($carrier_id);

        if($carrier->id) {

            //Make this method testable and mockable by using our injected $artifact member.
            $artifacts = $this->artifact->select(array('artifacts.id',  'artifacts.name', 'artifacts.created_at'))->where('carrier_id', '=', $carrier_id);

            return Datatables::of($artifacts)
            // ->edit_column('created_at','{{{ Carbon::now()->diffForHumans(Carbon::createFromFormat(\'Y-m-d H\', $test)) }}}')

            //See below - need to change name of added column - archive_id is automatically added at the end.
            ->add_column('thumbnail', '<a href="{{{ URL::to(\'carriers/' . $carrier_id. '/artifacts/\' . $id ) }}}"> <img src="{{{ URL::to(\'/artifact/' . $carrier->archive_id . '/thumbnails/\' . $name ) }}}"/> </a>', 0)

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

    /**
     * Send an artifact image or default thumbnail.
     *
     * @return file
     */
    public function send_image($archive_id, $style, $name)
    {
        $path = Config::get('workflow.repository') . $archive_id . DIRECTORY_SEPARATOR . $style . DIRECTORY_SEPARATOR . $name;
        //return Response::download($path, $name, $headers);
        return Response::download($path);
    }
}