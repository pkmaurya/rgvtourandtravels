<?php

class SMTPMailer {
    private $host = 'smtp.gmail.com';
    private $port = 587;
    private $username = 'creatorgenix2022@gmail.com';
    private $password = 'yxobfhmtnofwhgem';
    private $debug = false;

    public function send($to, $subject, $body) {
        $socket = fsockopen($this->host, $this->port, $errno, $errstr, 30);
        if (!$socket) {
            return "Error: $errstr ($errno)";
        }

        $this->read($socket); // banner

        $this->cmd($socket, 'EHLO ' . gethostname());
        $this->cmd($socket, 'STARTTLS');
        
        // Upgrade to TLS
        stream_socket_enable_crypto($socket, true, STREAM_CRYPTO_METHOD_TLS_CLIENT);
        
        $this->cmd($socket, 'EHLO ' . gethostname());
        $this->cmd($socket, 'AUTH LOGIN');
        $this->cmd($socket, base64_encode($this->username));
        $this->cmd($socket, base64_encode($this->password));
        
        $this->cmd($socket, "MAIL FROM: <$this->username>");
        $this->cmd($socket, "RCPT TO: <$to>");
        $this->cmd($socket, 'DATA');

        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=utf-8\r\n";
        $headers .= "To: $to\r\n";
        $headers .= "From: Incredible India Tours <$this->username>\r\n";
        $headers .= "Subject: $subject\r\n";

        $message = "$headers\r\n$body\r\n.\r\n";
        
        $this->cmd($socket, $message, false); // Don't wait for 250 yet, just send data
        // Actually for DATA, we send headers+body+. then wait for 250 OK
        
        $response = $this->read($socket);
        // We expect "250 OK" after data end

        $this->cmd($socket, 'QUIT');
        fclose($socket);

        return true;
    }

    private function cmd($socket, $cmd, $expectResponse = true) {
        if ($this->debug) echo "C: $cmd<br>";
        fputs($socket, $cmd . "\r\n");
        if ($expectResponse) {
            return $this->read($socket);
        }
        return null; // When not expecting response immediately or handling it manually
    }

    private function read($socket) {
        $response = '';
        while ($str = fgets($socket, 515)) {
            $response .= $str;
            if (substr($str, 3, 1) == ' ') {
                break;
            }
        }
        if ($this->debug) echo "S: $response<br>";
        return $response;
    }
}
