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
     * Inject the models and repository service class.
     * @param Artifact $artifact
     * @param Carrier $carrier
     * @param FileRepository $fileRepo
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
        $carrier = $this->carrier->find($carrier_id);

        if ($artifact->id) {
            // Title
            $title = Lang::get('artifact/title.artifact_show');

            // Show the page
            return View::make('artifact/show', compact('carrier', 'artifact', 'title'));

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
            
            // Check to see if a file has been uploaded
            if (Input::hasFile('artifact')) {

                $uploaded = Input::file('artifact')->getRealPath();
                //$count = $carrier->artifacts()->count() + 1;
                $name =  $carrier->archive_id . '-' . uniqid() . '.' . strtolower(Input::file('artifact')->getClientOriginalExtension());

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
     * Remove artifact page.
     *
     * @param $artifact
     * @return Response
     */
    public function delete($carrier_id, $id)
    {
        $artifact = $this->artifact->where('id', '=', $id)->where('carrier_id', '=', $carrier_id)->first();
        $carrier = $this->carrier->find($carrier_id);

        // Title
        $title = Lang::get('artifact/title.artifact_delete');

        if ($artifact) {
            // Show the record
            return View::make('artifact/delete', compact('carrier', 'artifact', 'title'));

        } else {
            // Redirect to the artifact management page
            return Redirect::to('carriers/' . $carrier_id. '/artifacts')->with('error', Lang::get('artifact/messages.does_not_exist'));
        }
    }

    /**
     * Remove the specified artifact from storage.
     * @internal param $id
     * @return Response
     */
    public function destroy($carrier_id, $id)
    {
        $artifact = $this->artifact->where('id', '=', $id)->where('carrier_id', '=', $carrier_id)->first();

        if ($this->fileRepo->delete_file($artifact->carrier->archive_id, $artifact->name)) {

            // Was the artifact deleted?
            if ($artifact->delete()) {
                // Redirect to the artifact management page
                return Redirect::to('carriers/' . $carrier_id)->with('success', Lang::get('artifact/messages.delete.success'));
            }

            // There was a problem deleting the artifact
            return Redirect::to('carriers/' . $carrier_id)->with('error', Lang::get('artifact/messages.delete.error'));
        } else {
            // There was a problem deleting the artifact
            return Redirect::to('carriers/' . $carrier_id)->with('error', 'There was a problem removing the artificat file.');
        }
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
                        <li><a href="{{{ URL::to(\'/artifact/' . $carrier->archive_id . '/download/\' . $name ) }}}"> {{{ Lang::get(\'button.download\') }}}</a></li>
                        <li><a href="{{{ URL::to(\'carriers/' . $carrier_id. '/artifacts/\' . $id . \'/delete\' ) }}}">{{{ Lang::get(\'button.delete\') }}}</a></li>
                      </ul>
                    </div>')

            ->remove_column('id')

            ->make();

        }
    }

    /**
     * Send an artifact to the clienet. Either thumbnail, preview, or download.
     *
     * @return file
     */
    public function send_artifact($archive_id, $mode, $name)
    { 
        if ($mode == "thumbnails" || $mode == "previews") {
            
            $ext = pathinfo($name, PATHINFO_EXTENSION);

            switch (strtolower($ext)) {
                case 'jpeg':
                case 'gif':
                case 'jpg':
                case 'tiff':
                case 'tif':
                    $path = Config::get('workflow.repository') . $archive_id . DIRECTORY_SEPARATOR . "artifacts" . DIRECTORY_SEPARATOR . $mode . DIRECTORY_SEPARATOR . $name;
                    break;
                case 'pdf':
                    $path = public_path() . DIRECTORY_SEPARATOR . "img" . DIRECTORY_SEPARATOR . "icons" . DIRECTORY_SEPARATOR . "icon_pdf.png";
                    break;
                case 'doc':
                case 'docx':
                    $path = public_path() . DIRECTORY_SEPARATOR . "img" . DIRECTORY_SEPARATOR . "icons" . DIRECTORY_SEPARATOR . "icon_word.png";
                    break;
                default:
                    $path = public_path() . DIRECTORY_SEPARATOR . "img" . DIRECTORY_SEPARATOR . "icons" . DIRECTORY_SEPARATOR . "icon_generic.png";
                    break;
            }


            $lifetime = 0; //seconds

            if (file_exists($path)){
                $filetime = filemtime($path);
                $etag = md5($filetime . $path);
                $time = gmdate('r', $filetime);
                $expires = gmdate('r', $filetime + $lifetime);
                $length = filesize($path);
         
                $headers = array(
                    'Content-Disposition' => 'inline; filename="' . $name . '"',
                    'Last-Modified' => $time,
                    'Cache-Control' => 'must-revalidate',
                    'Expires' => $expires,
                    'Pragma' => 'public',
                    'Etag' => $etag,
                );
         
                $headerTest1 = isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) && $_SERVER['HTTP_IF_MODIFIED_SINCE'] == $time;
                $headerTest2 = isset($_SERVER['HTTP_IF_NONE_MATCH']) && str_replace('"', '', stripslashes($_SERVER['HTTP_IF_NONE_MATCH'])) == $etag;
                if ($headerTest1 || $headerTest2) { //image is cached by the browser, we dont need to send it again
                    return Response::make('', 304, $headers);
                }

                $finfo = finfo_open(FILEINFO_MIME_TYPE); 
                $mime = finfo_file($finfo, $path); 
         
                $headers = array_merge($headers, array(
                    'Content-Type' => $mime,
                    'Content-Length' => $length,
                        ));
         
                return Response::make(File::get($path), 200, $headers);
            }

        } else {
           $path = Config::get('workflow.repository') . $archive_id . DIRECTORY_SEPARATOR . "artifacts" . DIRECTORY_SEPARATOR . $name;
           return Response::download($path); 
        }
    }
}
