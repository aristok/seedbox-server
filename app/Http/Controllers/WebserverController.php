<?php

namespace App\Http\Controllers;

use App\Webserver;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class WebserverController extends Controller
{

    /**
     * Throws an HttpException if client does not give the secret
     *
     * @param Request $request
     *
     * @throws HttpException
     * @throws NotFoundHttpException
     */
    protected function validateClient(Request $request): void
    {
        if (strcmp(env('CLIENT_SECRET'), $request->headers->get('client')) !== 0) {
            abort(403, 'Unauthorized action.');
        }
    }

    /**
     * @param bool $success
     * @param $payload
     * @return array
     */
    protected function getResultBody(bool $success, $payload)
    {
        return [
            "success" => true,
            "result" => $payload,
        ];
    }

    /**
     * @param int $id
     * @return Webserver|null
     *
     * @throws HttpException
     * @throws NotFoundHttpException
     */
    protected function getRecordById(int $id): ?Webserver
    {
        if ($webserver = Webserver::find($id)) {
            return $webserver;
        }
        abort(404, 'Resource not found');
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $this->validateClient($request);

        return response()->json($this->getResultBody(true, Webserver::all()));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request): JsonResponse
    {
        $this->validateClient($request);

        $webserver = new Webserver;

        $webserver->status = $request->status;
        $webserver->fqdn = $request->fqdn;
        $webserver->description = $request->description;

        if ($webserver->save()) {
            return response()->json($this->getResultBody(true, $webserver));
        }

        return response()->json($this->getResultBody(true, "Failed to save new webserver"));
    }

    /**
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function show(Request $request, int $id): JsonResponse
    {
        $this->validateClient($request);
        return response()->json($this->getResultBody(true, $this->getRecordById($id)));
    }

    /**
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $this->validateClient($request);
        $webserver = $this->getRecordById($id);

        $webserver->status = $request->input('status');
        $webserver->fqdn = $request->input('fqdn');
        $webserver->description = $request->input('description');

        if ($webserver->save()) {
            return response()->json($this->getResultBody(true, $webserver));
        }
        return response()->json($this->getResultBody(true, "Failed to update new webserver"));
    }

    /**
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     * @throws \Exception
     */
    public function destroy(Request $request, int $id): JsonResponse
    {
        $this->validateClient($request);
        $webserver = $this->getRecordById($id);
        if ($webserver->delete()) {
            return response()->json(
                $this->getResultBody(true, 'webserver ' . $webserver->fqdn . ' removed successfully')
            );
        }
        return response()->json($this->getResultBody(true, "Failed to remove webserver"));
    }
}
