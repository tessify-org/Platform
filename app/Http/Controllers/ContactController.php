<?php

namespace App\Http\Controllers;

class ContactController extends Controller
{
    public function getContact()
    {
        return view("pages.contact.form", []);
    }

    public function postContact()
    {

    }
}