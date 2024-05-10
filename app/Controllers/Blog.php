<?php

namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\Blog as BlogModel;

class Blog extends BaseController
{

    use \CodeIgniter\API\ResponseTrait;
    public function index(): string
    {
        return view('welcome_message');
    }

    public function show($id)
    {
        $blogs = new BlogModel();
        $blog = $blogs->find($id);
        return $this->respond($blog);
    }

    public function create()
    {
        $data = $this->request->getPost();
        $blog = new BlogModel;
        $id = $blog->insert($data);

        if($blog->error())
        {
            return $this->fail($blog->errors());
        }
        if($id===false)
        {
            return $this->failServerError();
        }
        $blogs = $blog->getwhere(['id'=>$id])->getResult();
        return $this->respondCreated($blogs);
    }
}
