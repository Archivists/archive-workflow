<?php
/*
|--------------------------------------------------------------------------
| StatusChange Controller
|--------------------------------------------------------------------------
| 
| See routes.php
| 
| This is a dedicated (and RESTFul) controller that will change a single
| field on the Carrier model - the status field. As such, only the 
| edit and update views (get and put) methods will be present in this
| controller.
| 
| This follows RESTful best practises, where changes to an atrribute
| has its own business rules, and therefore its own URL (or resource endpoint)
|
|
*/

class StatusChangeController extends BaseController
{
    /**
     * Carrier Model
     * @var Carrier
     */
    protected $carrier;

    /**
     * Status Model
     * @var Status
     */
    protected $status;

    /**
     * StatusActions Service Class
     * @var StatusActions
     */
    protected $actions;

    /**
     * Inject the model.
     * @param Carrier $carrier
     */
    public function __construct(Carrier $carrier, Status $status, StatusActions $actions)
    {
        parent::__construct();
        $this->carrier = $carrier;
        $this->status = $status;
        $this->actions = $actions;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $carrier
     * @return Response
     */
    public function edit($id)
    {
        $carrier = $this->carrier->find($id);

        if ($carrier->id) {

        } else {
            // Redirect to the carrier management page
            return Redirect::to('carriers')->with('error', Lang::get('carrier/messages.does_not_exist'));
        }

        // Title
        $title = Lang::get('carrier/title.carrier_update');

        // All carrier types
        $statuses = $this->status->sequence()->get();

         // Selected status
        $selectedStatus = Input::old('status', array());

        // Show the page
        return View::make('statuschange/edit', compact('carrier', 'title', 'statuses', 'selectedStatus'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param $carrier
     * @return Response
     */
    public function update($id)
    {
        $carrier = $this->carrier->find($id);

        $rules = array(
                'shelf_number'=> 'required|alpha_dash|unique:carriers,shelf_number,' . $carrier->id,
                'sides' => 'required',
                'status' => 'required',
                'carrier_type'=> 'required'
            );

        // Validate the inputs
        $validator = Validator::make(Input::all(), $rules);

        // Check if the form validates with success
        if ($validator->passes()) {
            
            // Get the inputs, with some exceptions
            $inputs = Input::except('csrf_token');

            $carrierType = $this->carrierType->find($inputs['carrier_type']);
            $status = $this->status->find($inputs['status']);

            if ($carrierType && $status) {

                $carrier->shelf_number = $inputs['shelf_number'];
                $carrier->sides = $inputs['sides'];
                $carrier->notes = $inputs['notes'];
                $carrier->status()->associate($status);
                $carrier->carrierType()->associate($carrierType);

                // Was the carrier updated?
                if ($carrier->save($rules)) {
                    // Redirect to the carrier page
                    return Redirect::to('carriers/' . $carrier->id)->with('success', Lang::get('carrier/messages.update.success'));
                } else {
                    // Redirect to the carrier page
                    return Redirect::to('carriers/' . $carrier->id . '/edit')->with('error', Lang::get('carrier/messages.update.error'));
                }
            }
            else {
                return Redirect::to('carriers/' . $carrier->id . '/edit')->with('error', Lang::get('carrier/messages.update.error'));
            }
        } else {
            // Form validation failed
            return Redirect::to('carriers/' . $carrier->id . '/edit')->withInput()->withErrors($validator);
        }
    }
}
