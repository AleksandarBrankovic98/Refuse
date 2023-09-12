<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\FormTemplate;
use App\Model\UserAgreement;
use App\Model\UserAgreementSeller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

class UserAgreementController extends Controller
{
    public $viewPath = "back.pages.user-agreement.";

    /**
     * list user agreement
     *
     * @return void
     * @author Bhavesh Vyas
     */
    public function index()
    {
        //sachin
        $userAgreements = UserAgreement::select('user_agreements.*', 'form_templates.template_name')
            ->leftjoin('form_templates', 'form_templates.id', 'user_agreements.template_id')
            ->get();
        //sachin
        return view($this->viewPath . 'index', compact('userAgreements'));
    }

    /**
     * create user agreement
     *
     * @return void
     * @author Bhavesh Vyas
     */
    public function create()
    {
        $response = [
            'success' => true,
            'html'    => view($this->viewPath . 'form')->render(),
        ];
        return response()->json($response, 200);
    }

    /**
     * store user agreement
     *
     * @param Request $request
     * @return void
     * @author Bhavesh Vyas
     */
    public function store(Request $request)
    {
        $rules = [
            'template_id' => ['required'],
            'content'     => ['required'],
            'seller_id'   => ['required'],
        ];

        $message = [
            'template_id.required'    => "The field is required.",
            'agreement_date.required' => "The field is required.",
            'content.required'        => "The field is required.",
            'seller_id.required'      => "The field is required.",
        ];

        $validator = Validator::make($request->all(), $rules, $message);

        if ($validator->fails()) {
            $response = [
                'success' => false,
                'errors'  => $validator->errors()->toArray(),
            ];
            return response()->json($response, 400);
        }

        $userAgreement                             = new UserAgreement();
        $userAgreement->template_id                = $request->template_id;
        $userAgreement->agreement_date             = date("Y-m-d");
        $userAgreement->content                    = $request->content;
        $userAgreement->agreement_template_content = $request->content;
        $userAgreement->admin_id                   = auth()->id();
        $userAgreement->created_at                 = date("Y-m-d H:i:s");
        $userAgreement->updated_at                 = date("Y-m-d H:i:s");
        $userAgreement->save();

        $replaceSignature = "";
        if (count($request->seller_id) > 0) {
            foreach ($request->seller_id as $sellerId) {
                $replaceSignature .= "<p>{SIGNATURE_$sellerId}</p>";
                $userAgreementSeller                    = new UserAgreementSeller();
                $userAgreementSeller->user_agreement_id = $userAgreement->id;
                $userAgreementSeller->user_id           = $sellerId;
                $userAgreementSeller->signature_key     = "{SIGNATURE_$sellerId}";
                $userAgreementSeller->created_at        = date("Y-m-d H:i:s");
                $userAgreementSeller->updated_at        = date("Y-m-d H:i:s");
                $userAgreementSeller->save();
            }
        }

        if ($replaceSignature != "") {
            $replaceSignature .= "<p>{SIGNATURE_USER}</p>";
            $userAgreement->content = str_replace("<p>{SIGNATURE_USER}</p>", $replaceSignature, $userAgreement->content);
            $userAgreement->save();
        }

        runCURL(url("api/agreement/{$userAgreement->id}/mail"));

        $response = [
            'success' => true,
            'message' => "Contract has been created successfully.",
        ];

        return response()->json($response, 200);
    }

    /**
     * get template content
     *
     * @param int $templateId
     * @return void
     * @author Bhavesh Vyas
     */
    public function getTemplateData(int $templateId)
    {
        $template = FormTemplate::find($templateId);

        $response = [
            'success' => true,
            'content' => $template->content,
        ];

        return response()->json($response, 200);
    }

    /**
     * edit user agreement
     *
     * @param Request $request
     * @param int $userAgreementId
     * @return void
     * @author Bhavesh Vyas
     */
    public function edit(Request $request, int $userAgreementId)
    {
        $userAgreement = UserAgreement::find($userAgreementId);

        $userAgreementSellerIds = UserAgreementSeller::where("user_agreement_id", $userAgreementId)->pluck("user_id")->all();

        $response = [
            'success'    => true,
            'userSeller' => $userAgreementSellerIds,
            'html'       => view($this->viewPath . 'edit', compact('userAgreement', 'userAgreementSellerIds'))->render(),
        ];

        return response()->json($response, 200);
    }

    /**
     * update user agreement
     *
     * @param Request $request
     * @param integer $userAgreementId
     * @return void
     * @author Bhavesh Vyas
     */
    public function update(Request $request, int $userAgreementId)
    {
        $rules = [
            'template_id' => ['required'],
            'content'     => ['required'],
            'seller_id'   => ['required'],
        ];

        $message = [
            'template_id.required'    => "The field is required.",
            'agreement_date.required' => "The field is required.",
            'content.required'        => "The field is required.",
            'seller_id.required'      => "The field is required.",
        ];

        $validator = Validator::make($request->all(), $rules, $message);

        if ($validator->fails()) {
            $response = [
                'success' => false,
                'errors'  => $validator->errors()->toArray(),
            ];
            return response()->json($response, 400);
        }

        $userAgreement          = UserAgreement::find($userAgreementId);
        $userAgreementSellerIds = UserAgreementSeller::where("user_agreement_id", $userAgreementId)->pluck("user_id")->all();

        $userAgreement->template_id                = $request->template_id;
        $userAgreement->agreement_date             = date("Y-m-d");
        $userAgreement->content                    = $request->content;
        $userAgreement->agreement_template_content = $request->content;
        $userAgreement->admin_id                   = auth()->id();
        $userAgreement->updated_at                 = date("Y-m-d H:i:s");
        $userAgreement->save();

        $replaceSignature = "";
        if (count($request->seller_id) > 0) {
            foreach ($request->seller_id as $sellerId) {
                if (!in_array($sellerId, $userAgreementSellerIds)) {
                    $replaceSignature .= "<p>{SIGNATURE_$sellerId}</p>";
                    $userAgreementSeller                    = new UserAgreementSeller();
                    $userAgreementSeller->user_agreement_id = $userAgreement->id;
                    $userAgreementSeller->user_id           = $sellerId;
                    $userAgreementSeller->signature_key     = "{SIGNATURE_$sellerId}";
                    $userAgreementSeller->created_at        = date("Y-m-d H:i:s");
                    $userAgreementSeller->updated_at        = date("Y-m-d H:i:s");
                    $userAgreementSeller->save();
                }
            }
        }

        if ($replaceSignature != "") {
            $replaceSignature .= "<p>{SIGNATURE_USER}</p>";
            $userAgreement->content = str_replace("<p>{SIGNATURE_USER}</p>", $replaceSignature, $userAgreement->content);
            $userAgreement->save();
        }

        runCURL(url("api/agreement/{$userAgreement->id}/mail"));

        $response = [
            'success' => true,
            'message' => "Contract has been updated successfully.",
        ];

        return response()->json($response, 200);
    }

    /**
     * delete user agreement
     *
     * @param integer $userAgreementId
     * @return void
     * @author Bhavesh Vyas
     */
    public function delete(int $userAgreementId)
    {
        $userAgreement = UserAgreement::find($userAgreementId);
        if ($userAgreement) {
            //sachin changed the id
            UserAgreementSeller::where("id", $userAgreementId)->delete();
            $userAgreement->delete();
        }

        Alert::success('Success', 'User Agreement has been deleted successfully.');
        return redirect()->back();
    }

}