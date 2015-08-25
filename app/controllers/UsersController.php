<?php

class UsersController extends Phalcon\DI\Injectable {

    private $app;

    public function __construct($app)
    {
        $this->app = $app;
        $this->app->response->setContentType('application/json', 'utf-8');
    }

    public function preview()
    {
        $usr = new Users();
        $this->app->response->setContentType('application/json', 'utf-8');
        $this->app->response->setJsonContent($usr->getList());
        return $this->app->response;
    }

    public function create()
    {
        $jarray = $this->app->request->getJsonRawBody();

        if (($jarray != NULL) && ($jarray->login && $jarray->password)) {
            $user = new Users();
            $userId = $user->setUser($jarray->login, $jarray->password);
            if ($userId) {
                $this->app->response
                        ->setStatusCode(201, "Created")
                        ->setJsonContent(array('status' => 'OK', 'data' => $userId));
            } else {
                $this->app->response
                        ->setStatusCode(409, "Conflict")
                        ->setJsonContent(array('status' => 'ERROR', 'data' => 'duplicated User login'));
            }
        } else {
            $this->app->response
                    ->setStatusCode(400, "Bad Request")
                    ->setJsonContent(array('status' => 'ERROR', 'data' => 'wrong JSON input'));
        }
        return $this->app->response;
    }

    public function info($id)
    {
        $user = Users::findFirst($id);
        if ($user) {
            $this->app->response->setContentType('application/json', 'utf-8');
            $this->app->response->setJsonContent($user->getUser());
        } else {
            $this->app->response
                    ->setStatusCode(404, "Not Found")
                    ->setJsonContent(array('status' => 'ERROR', 'data' => 'User not found'));
        }
        return $this->app->response;
    }

    public function update($id)
    {
        if (!empty($this->app['auth']['id']) && ($this->app['auth']['id'] == $id)) {
            $jarray = $this->app->request->getJsonRawBody();

            if (($jarray != NULL) && ($jarray->login && $jarray->password)) {
                $user = Users::findFirst($id);
                $userId = $user->updateUser($jarray->login, $jarray->password);
                if ($userId) {
                    $this->app->response
                            ->setStatusCode(200, "OK")
                            ->setJsonContent(array('status' => 'OK', 'data' => $userId));
                } else {
                    $this->app->response
                            ->setStatusCode(409, "Conflict")
                            ->setJsonContent(array('status' => 'ERROR', 'data' => 'duplicated User login'));
                }
            } else {
                $this->app->response
                        ->setStatusCode(400, "Bad Request")
                        ->setJsonContent(array('status' => 'ERROR', 'data' => 'wrong JSON input'));
            }
        } else {
            $this->app->response->setStatusCode(401, "Unauthorized")
                    ->setJsonContent(array('status' => 'ERROR', 'data' => 'Access is not authorized'));
        }
        return $this->app->response;
    }

    public function delete($id)
    {
        if (!empty($this->app['auth']['id']) && ($this->app['auth']['id'] == $id)) {
            $user = Users::findFirst($id);
            if ($user->delete() == false) {
                $this->app->response
                        ->setStatusCode(400, "Bad Request")
                        ->setJsonContent(array('status' => 'ERROR', 'data' => 'User not deleted'));
            } else {
                $this->app->response
                        ->setStatusCode(204, "OK")
                        ->setJsonContent(array('status' => 'OK', 'data' => 'User deleted'));
            }
        } else {
            $this->app->response->setStatusCode(401, "Unauthorized")
                    ->setJsonContent(array('status' => 'ERROR', 'data' => 'Access is not authorized'));
        }
        return $this->app->response;
    }

}
