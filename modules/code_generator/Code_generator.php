<?php

/**
 * Code Generator Controller (Trongate v2 Structure)
 *
 * Provides endpoints for the code generator tooling.
 */
class Code_generator extends Trongate {

    private string $api_base_url = 'http://localhost/trongate_live5/';

    public function index(): void {
        $data['api_base_url'] = $this->api_base_url;
        $this->view('code_generator_template', $data);
    }

    public function choose_url_slug(): void {
        $data = [
            'starter_content_url' => $this->api_base_url . 't2_api-code_generator/choose_url_slug',
            'api_base_url' => $this->api_base_url
        ];

        json($data, true);
    }

    public function fetch_starter_content(): void {
        $target_url = $this->api_base_url . 't2_api-code_generator/home';
        $response = $this->perform_get_request($target_url);
        http_response_code($response['status_code']);
        echo $response['response_body'];
    }

    public function fetch_mod_list(): void {
        $module_dir = APPPATH . DIRECTORY_SEPARATOR . 'modules';
        $directories = [];

        if (is_dir($module_dir)) {
            foreach (scandir($module_dir) as $item) {
                $full_path = $module_dir . DIRECTORY_SEPARATOR . $item;
                if ($item !== '.' && $item !== '..' && is_dir($full_path)) {
                    $directories[] = strtolower($item);
                }
            }
        }

        http_response_code(200);
        echo json_encode($directories);
    }

    /**
     * Performs a POST request to the specified URL with optional parameters and headers.
     *
     * @param string $url The URL to send the POST request to.
     * @param array $params An associative array of POST data.
     * @param array $headers Optional associative array of headers (e.g. ['Content-Type' => 'application/x-www-form-urlencoded']).
     * @return array An array containing 'response_body', 'status_code', and 'curl_error'.
     */
    private function perform_post_request(string $url, array $params = [], array $headers = []): array {
        $formatted_headers = [];
        foreach ($headers as $key => $value) {
            $formatted_headers[] = $key . ': ' . $value;
        }

        $ch = curl_init();

        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query($params),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $formatted_headers,
            CURLOPT_TIMEOUT => 60,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_MAXREDIRS => 5,
        ]);

        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curl_error = curl_error($ch);

        curl_close($ch);

        return [
            'response_body' => $response,
            'status_code' => $http_code,
            'curl_error' => $curl_error
        ];
    }

    public function properties_builder(): void {
        $target_url = $this->api_base_url . 'desktop_app_api/properties_builder';
        $response = $this->perform_get_request($target_url);

        if (!empty($response['curl_error'])) {
            http_response_code(500);
            echo json_encode(['error' => 'Request failed: ' . $response['curl_error']]);
            return;
        }

        if ($response['status_code'] !== 200) {
            http_response_code($response['status_code']);
            echo json_encode(['error' => 'API returned HTTP code ' . $response['status_code']]);
            return;
        }

        header('Content-Type: text/html; charset=utf-8');
        echo $response['response_body'];
    }

    /**
     * Performs a GET request to the specified URL with optional headers.
     *
     * @param string $url The URL to send the GET request to.
     * @param array $headers Optional associative array of headers.
     * @return array An array containing 'response_body', 'status_code', and 'curl_error'.
     */
    private function perform_get_request(string $url, array $headers = []): array {
        $formatted_headers = [];
        foreach ($headers as $key => $value) {
            $formatted_headers[] = $key . ': ' . $value;
        }

        $ch = curl_init();

        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $formatted_headers,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_MAXREDIRS => 5,
        ]);

        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curl_error = curl_error($ch);

        curl_close($ch);

        return [
            'response_body' => $response,
            'status_code' => $http_code,
            'curl_error' => $curl_error
        ];
    }

}

