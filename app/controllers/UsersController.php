<?php

class UsersController extends Phalcon\DI\Injectable {

    private $app;

    public function __construct($app)
    {
        $this->app = $app;
        $this->app->response->setContentType('application/json', 'utf-8');
    }

    /**
     * Return list of Users
     * 
     * @return Response
     */
    public function preview()
    {
        $usr = new Users();
        $this->app->response->setJsonContent($usr->getList());
        return $this->app->response;
    }

    /**
     * Create new User
     * 
     * @return Response
     */
    public function create()
    {
        $jarray = $this->app->request->getJsonRawBody();

        if (($jarray != NULL) && ($jarray->login && $jarray->password && $jarray->pubkey)) {
            $user = new Users();
            $userId = $user->setUser($jarray->login, $jarray->password, $jarray->pubkey);
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

    /**
     * Return detailed data about User
     * 
     * @param integer $id
     * @return Response
     */
    public function info($id)
    {
        $user = Users::findFirst($id);
        if ($user) {
            $this->app->response->setJsonContent($user->getUser());
        } else {
            $this->app->response
                    ->setStatusCode(404, "Not Found")
                    ->setJsonContent(array('status' => 'ERROR', 'data' => 'User not found'));
        }
        return $this->app->response;
    }

    /**
     * Update User data
     * 
     * @param integer $id
     * @return Response
     */
    public function update($id)
    {
        if (!empty($this->app['auth']['id']) && ($this->app['auth']['id'] == $id)) {
            $jarray = $this->app->request->getJsonRawBody();

            if (($jarray != NULL) && ($jarray->login && $jarray->password && $jarray->pubkey && $jarray->old_password)) {
                $user = Users::findFirst($id);
                $userId = $user->updateUser($jarray->login, $jarray->password, $jarray->pubkey, $jarray->old_password);
                if ($userId) {
                    $this->app->response
                            ->setJsonContent(array('status' => 'OK', 'data' => $userId));
                } else {
                    $this->app->response
                            ->setStatusCode(409, "Conflict")
                            ->setJsonContent(array('status' => 'ERROR', 'data' => 'Validation (2nd level) fail or data error'));
                }
            } else {
                $this->app->response
                        ->setStatusCode(400, "Bad Request")
                        ->setJsonContent(array('status' => 'ERROR', 'data' => 'wrong JSON input'));
            }
        } else {
            $this->app->response
                    ->setStatusCode(401, "Unauthorized")
                    ->setJsonContent(array('status' => 'ERROR', 'data' => 'Access is not authorized'));
        }
        return $this->app->response;
    }

    /**
     * Delete User
     * 
     * @param integer $id
     * @return Response
     */
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
            $this->app->response
                    ->setStatusCode(401, "Unauthorized")
                    ->setJsonContent(array('status' => 'ERROR', 'data' => 'Access is not authorized'));
        }
        return $this->app->response;
    }

}
