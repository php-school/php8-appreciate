<?php

class FailedResponse implements \Stringable {

    public function __construct(private string $status, private string $error)
    {
    }

    public function __toString()
    {
        return "Status: {$this->status}\nError: {$this->error}\n";
    }
}

$request = json_decode(file_get_contents('php://input'), true);

if (!$request['success']) {
    log_failure(new FailedResponse($request['status'], $request['error']));
}
