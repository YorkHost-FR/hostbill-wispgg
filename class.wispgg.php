<?php

/**
 * Class wispgg
 *
 * Hosting/Provisioning module
 *
 */
class wispgg extends HostingModule {
    use \Components\Traits\LoggerTrait;

    /**
     * Toggle file logging
     * @var bool
     */
    protected $fileLoggingEnabled = false;

    /**
     * Module repository identifier
     * @var string
     */
    protected $_repository = 'hosting_wispgg';

    /**
     * Module version
     * @var string
     */
    protected $version = '1.0.1';

    /**
     * Module name
     * @var string
     */
    protected $modname = 'Wisp.gg';

    /**
     * Module description
     * @var string
     */
    protected $description = 'Wisp.gg module for HostBill';

    /**
     * App connection fields
     * @var array
     */
    protected $serverFields = [
        self::CONNECTION_FIELD_USERNAME    => false,
        self::CONNECTION_FIELD_PASSWORD    => false,
        self::CONNECTION_FIELD_INPUT1      => true,  // API Application Key
        self::CONNECTION_FIELD_INPUT2      => false,
        self::CONNECTION_FIELD_CHECKBOX    => true,
        'enable_file_logs' => [
            'name'        => 'enable_file_logs',
            'value'       => false,
            'type'        => 'check',
            'description' => 'Activer les logs sur fichier (/tmp/wispgg.log)',
            'forms'       => 'checkbox',
        ],
        self::CONNECTION_FIELD_HOSTNAME    => true,
        self::CONNECTION_FIELD_IPADDRESS   => false,
        self::CONNECTION_FIELD_MAXACCOUNTS => false,
        self::CONNECTION_FIELD_STATUSURL   => false,
        self::CONNECTION_FIELD_TEXTAREA    => false,
    ];

    /**
     * Field descriptions
     * @var array
     */
    protected $serverFieldsDescription = [
        self::CONNECTION_FIELD_INPUT1 => 'Api Application Key',
    ];

    /**
     * Product option fields
     * @var array
     */
    protected $options = [

        'CPU' => [
            'value' => '',
            'description' => 'The amount of cpu limit you want the server to have',
            'type' => 'input',
            'default' => '',
            'forms' => 'input',
            'variable' => 'cpu',
            '_tab' => 'resources',
        ],
        'Disk Space' => [
            'value' => '',
            'description' => 'The amount of storage you want the server to use',
            'type' => 'input',
            'default' => '',
            'forms' => 'input',
            'variable' => 'disk',
            '_tab' => 'resources',
        ],
        'Disk Space Unit' => [
            'value' => 'MB',
            'description' => 'Unit for disk size set',
            'type' => 'select',
            'default' => ['MB','GB'],
            '_tab' => 'resources',
        ],
        'Memory' => [
            'value' => '',
            'description' => 'The amount of memory you want the server to use',
            'type' => 'input',
            'default' => '',
            'forms' => 'input',
            'variable' => 'memory',
            '_tab' => 'resources',
        ],
        'Memory Space Unit' => [
            'value' => 'MB',
            'description' => 'Unit for memory/swap size set',
            'type' => 'select',
            'default' => ['MB','GB'],
            '_tab' => 'resources',
        ],
        'Swap' => [
            'value' => '',
            'description' => 'The amount of memory you want the server to use',
            'type' => 'input',
            'default' => '',
            'forms' => 'input',
            'variable' => 'swap',
            '_tab' => 'resources',
        ],
        'Block IO Weight' => [
            'value' => '',
            'description' => 'The amount of memory you want the server to use',
            'type' => 'input',
            'default' => '',
            'forms' => 'input',
            'variable' => 'block_io_weight',
            '_tab' => 'resources',
        ],
        'Databases' => [
            'value' => '',
            'description' => 'The total number of databases a user is allowed to create for this server. Leave blank to allow unlimited',
            'type' => 'input',
            'default' => '',
            'forms' => 'input',
            'variable' => 'database',
            '_tab' => 'resources',
        ],
        'Dedicated IP' => [
            'value' => '',
            'description' => 'Check if you want the server to have a dedicated IP',
            'type' => 'check',
            'default' => '',
            'forms' => 'checkbox',
            'variable' => 'dedicated',
            '_tab' => 'resources',
        ],
        'Allocations' => [
            'value' => '',
            'description' => 'Number of allocations allowed',
            'type' => 'input',
            'default' => '',
            'forms' => 'input',
            'variable' => 'allocation',
            '_tab' => 'resources',
        ],
        'Backups' => [
            'value' => '',
            'description' => 'The server\'s backups limit',
            'type' => 'input',
            'default' => '',
            'forms' => 'input',
            'variable' => 'backups',
            '_tab' => 'resources',
        ],
        'Location' => [
            'value' => '',
            'description' => 'Locations that nodes can be assigned',
            'type' => 'loadable',
            'default' => 'getLocations',
            'forms' => 'select',
            'variable' => 'location',
            '_tab' => 'resources',
        ],
        'Port Range' => [
            'value' => '',
            'description' => '',
            'type' => 'input',
            'default' => '',
            'forms' => 'input',
            'variable' => 'port_range',
            '_tab' => 'resources',
        ],
        'Nest' => [
            'value' => '',
            'description' => 'Select the Nest that this server will be grouped under.',
            'type' => 'loadable',
            'default' => 'getNests',
            'forms' => 'select',
            'variable' => 'nest',
            '_tab' => 'nest',
        ],
        'Egg' => [
            'value' => '',
            'description' => 'Select the Egg that will define how this server should operate.',
            'type' => 'loadable',
            'default' => 'getEggs',
            'forms' => 'select',
            'variable' => 'egg',
            '_tab' => 'nest',
        ],
        'Egg variables' => [
            'value' => '',
            'description' => 'Put egg variables value, eg. variable:value;. You can also use $value to replace it with Component. Also use ${allocation} to pick from one of the allocations or ${port} for main port.',
            'type' => 'textarea',
            'default' => '',
            'forms' => 'input',
            'variable' => 'egg_variable',
            '_tab' => 'nest',
        ],
        'Docker Image' => [
            'value' => '',
            'description' => 'This is the default Docker image that will be used to run this server.',
            'type' => 'input',
            'default' => '',
            'forms' => 'input',
            'variable' => 'docker_image',
            '_tab' => 'nest',
        ],
        'Startup script' => [
            'value' => '',
            'description' => 'The following data substitutes are available for the startup command: {{SERVER_MEMORY}}, {{SERVER_IP}}, and {{SERVER_PORT}}. They will be replaced with the allocated memory, server IP, and server port respectively.',
            'type' => 'textarea',
            'default' => '',
            'forms' => 'input',
            'variable' => 'startup_script',
            '_tab' => 'nest',
        ],
        'Data Pack' => [
            'value' => '',
            'description' => '',
            'type' => 'input',
            'default' => '',
            'forms' => 'input',
            'variable' => 'data_pack',
            '_tab' => 'nest',
        ],
    ];

    /**
     * Account detail fields
     * @var array
     */
        protected $details = [
        'device_id' => [
            'name' => 'device_id',
            'value' => false,
            'type' => 'input',
            'default' => false
        ],
        'uuid' => [
            'name' => 'uuid',
            'value' => false,
            'type' => 'input',
            'default' => false
        ],
        'username' => [
            'name' => 'username',
            'value' => false,
            'type' => 'input',
            'default' => false
        ],
        'password' => [
            'name' => 'password',
            'value' => false,
            'type' => 'input',
            'default' => false
        ],
        'domain' => [
            'name' => 'domain',
            'value' => false,
            'type' => 'input',
            'default' => false
        ],

    ];



    // Internal connection properties
    private $hostname;
    private $api_key;
    private $secure;
    private $response;
    private $response_code;

    /**
     * Initialize connection
     */
    public function connect($connect) {
        $this->hostname = $connect['host'];
        $this->api_key  = $connect['field1'];
        $this->secure   = $connect['secure'];

        $this->fileLoggingEnabled = !empty($connect['enable_file_logs']);

        $this->logger()->debug('WISP :: connect()', [
            'hostname' => $this->hostname,
            'api_key'  => $this->api_key,
            'secure'   => $this->secure,
            'fileLogs' => $this->fileLoggingEnabled,
        ]);
        if ($this->fileLoggingEnabled) {
            $this->logToFile('WISP :: connect()', [
                'hostname' => $this->hostname,
                'api_key'  => $this->api_key,
                'secure'   => $this->secure,
            ]);
        }
    }

    /**
     * Test connection endpoint
     */
    public function testConnection() {
        $check = $this->api('users');
        return $check !== false;
    }

    /**
     * Ensure full URL
     */
    private function _parseHostname() {
        $host = $this->hostname;
        $this->logger()->debug('WISP :: _parseHostname() input', ['hostname' => $host]);
        if ($this->fileLoggingEnabled) {
            $this->logToFile('WISP :: _parseHostname() input', ['hostname' => $host]);
        }

        $parsed = ($this->secure ? 'https://' : 'http://') . rtrim($host, '/');
        $this->logger()->debug('WISP :: _parseHostname() output', ['parsed_hostname' => $parsed]);
        if ($this->fileLoggingEnabled) {
            $this->logToFile('WISP :: _parseHostname() output', ['parsed_hostname' => $parsed]);
        }
        return $parsed;
    }

    /**
     * Generic API call
     */
    private function api($endpoint, $method = 'GET', $data = [], $ignoreErrors = []) {
        $url = $this->_parseHostname() . '/api/admin/' . $endpoint;
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);

        $verboseLog = fopen('/tmp/wispgg_curl_verbose.log','a+');
        curl_setopt($curl, CURLOPT_VERBOSE, true);
        curl_setopt($curl, CURLOPT_STDERR, $verboseLog);

        $post = json_encode($data);
        $headers = [
            'Authorization: Bearer ' . $this->api_key,
            'Accept: Application/vnd.pterodactyl.v1+json',
            'Content-Type: application/json',
        ];
        if (in_array($method, ['POST','PUT','PATCH'])) {
            curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
            $headers[] = 'Content-Length: ' . strlen($post);
        }
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($curl);
        $curlInfo = curl_getinfo($curl);
        $this->response = json_decode($result, true);
        $this->response_code = $curlInfo['http_code'];
        $err = curl_error($curl);

        curl_close($curl);
        fclose($verboseLog);

        if ($this->fileLoggingEnabled) {
            $this->logToFile('CURL INFO', $curlInfo);
            $this->logToFile('API CALL DEBUG', [
                'endpoint'=> $endpoint,
                'method'  => $method,
                'url'     => $url,
                'headers' => $headers,
                'data'    => $data,
                'response'=> $result,
                'code'    => $this->response_code,
            ]);
        }

        if ($err) {
            $this->addError('Connection error ' . $err);
            return false;
        } elseif (isset($this->response['errors'])) {
            foreach ($this->response['errors'] as $error) {
                if (in_array($error['code'], $ignoreErrors)) continue;
                $this->addError($error['code'] . ' details: ' . $error['detail']);
                return false;
            }
        }
        return $this->response;
    }

    /**
     * Create a new server
     */
    public function Create() {
        $egg = $this->getEgg($this->resource('nest'), $this->resource('egg'));
        $userId = $this->getOrCreateUser();
        if (!$userId) {
            $this->addError('Cannot create user');
            return false;
        }

        // Resource multipliers
        $multDisk = $this->options['Disk Space Unit']['value'] === 'GB' ? 1000 : 1;
        $multMem  = $this->options['Memory Space Unit']['value'] === 'GB' ? 1024 : 1;
        $multBack = 1000;

        $data = [
            'oom_disabled'             => false,
            'owner_id'                 => $userId,
            'external_id'              => $this->account_details['id'],
            'name'                     => 'Merci YorkHost.fr',
            'egg_id'                   => $this->resource('egg'),
            'docker_image'             => $egg['docker_image'],
            'startup'                  => $this->resource('startup_script'),
            'memory'                   => $this->resource('memory') * $multMem,
            'swap'                     => $this->resource('swap') * $multMem,
            'disk'                     => $this->resource('disk') * $multDisk,
            'io'                       => '500',
            'cpu'                      => $this->resource('cpu'),
            'database_limit'           => $this->resource('database'),
            'allocation_limit'         => $this->resource('allocation'),
            'backup_megabytes_limit'   => $this->resource('backups') * $multBack,
        ];

        $variables = $this->resource('egg_variable');
        if (!$variables) {
            $this->addError('Wrong or empty Egg variables');
            return false;
        }

        $nodeInfo = $this->getNodeAndAllocations();
        if (!$nodeInfo) {
            $this->addError('No suitable nodes with allocations');
            return false;
        }

        $data['node_id']               = $nodeInfo['node'];
        $data['primary_allocation_id'] = $nodeInfo['primary_allocation_id'][0];
        foreach ($nodeInfo['secondary_allocation_ids'] as $sec) {
            $data['secondary_allocations_ids'][] = $sec[0];
        }
        $data['start_on_completion'] = false;

        $data = $this->parseVariables($variables, $nodeInfo, $data);

        $server = $this->api('servers', 'POST', $data);
        if (is_array($server)) {
            $this->logger()->error('WISP :: Create() response', ['server_response' => $server]);
            if ($this->fileLoggingEnabled) {
                $this->logToFile('WISP :: Create() response', $server);
            }

            if (empty($server['attributes']['id'])) {
                $this->addError('Wrong or empty device ID');
                return false;
            }
            $this->details['device_id']['value'] = $server['attributes']['id'];
            $this->details['uuid']['value']      = $server['attributes']['uuid'];
            return true;
        }
        return false;
    }

    /**
     * Select a node and allocations
     */
    public function getNodeAndAllocations() {
        $needed = $this->resource('allocation') + 1;
        $location = $this->api('locations/' . $this->resource('location') . '?include=nodes');
        $nodes    = $location['attributes']['relationships']['nodes']['data'];
        foreach ($nodes as $n) {
            $nodeId = $n['attributes']['id'];
            $allocs = $this->api("nodes/{$nodeId}/allocations?filter[in_use]=false");
            if (count($allocs['data']) < $needed) continue;

            $info = ['node' => $nodeId];
            $count = 0;
            foreach ($allocs['data'] as $a) {
                $count++; if ($count > $needed) break;
                if ($count === 1) {
                    $info['primary_allocation_id']   = [$a['attributes']['id'], $a['attributes']['port']];
                } else {
                    $info['secondary_allocation_ids'][] = [$a['attributes']['id'], $a['attributes']['port']];
                }
            }
            $this->logger()->debug('WISP :: Selected node and allocations', $info);
            if ($this->fileLoggingEnabled) {
                $this->logToFile('WISP :: Selected node and allocations', $info);
            }
            return $info;
        }
        $this->addError('No allocations.');
        return false;
    }

    /**
     * Get or create user
     */
    public function getOrCreateUser() {
        $user = $this->getUser($this->client_data['id']);
        if (!$user) {
            return $this->createUser();
        }
        return $user['attributes']['id'];
    }

    /**
     * Create a new user
     */
    private function createUser() {
        $existing = $this->api('users?filter[email]=' . urlencode($this->client_data['email']));
        if ($existing['meta']['pagination']['total'] === 0) {
            $langMap = ['english'=>'en','czech'=>'cs_CZ'];
            $lang = $langMap[$this->client_data['language']] ?? 'en';
            $res = $this->api('users','POST', [
                'external_id'=> $this->client_data['id'],
                'username'   => $this->details['username']['value'],
                'password'   => $this->details['password']['value'],
                'email'      => $this->client_data['email'],
                'name_first' => $this->client_data['firstname'],
                'name_last'  => $this->client_data['lastname'] ?: $this->client_data['firstname'],
                'preferences'=> ['language'=>$lang]
            ]);
            if (in_array($this->response_code,[200,201])) {
                return $res['attributes']['id'];
            }
        } else {
            return $existing['data'][0]['attributes']['id'];
        }
        $this->addError('Failed to create user');
        return false;
    }

    /**
     * Fetch existing user
     */
    public function getUser($clientId) {
        $res = $this->api('users/external/' . $clientId,'GET',[],['NotFoundHttpException']);
        return $this->response_code===404 ? false : $res;
    }

    /**
     * Loadable: locations list
     */
    public function getLocations() {
        $locs = $this->api('locations');
        if (!$locs) return false;
        return array_map(fn($l)=>[$l['attributes']['id'],$l['attributes']['long']],$locs['data']);
    }

    /**
     * Loadable: nests list
     */
    public function getNests() { /* unchanged */ }

    /**
     * Loadable: eggs list
     */
    public function getEggs() { /* unchanged */ }

    /**
     * Fetch single egg
     */
    public function getEgg($nestId,$eggId) {
        $egg = $this->api("nests/{$nestId}/eggs/{$eggId}");
        return $egg? $egg['attributes'] : false;
    }

    /**
     * Get server details
     */
    public function getServerDetails() {
        $id = $this->details['device_id']['value'];
        $data = $this->api("servers/{$id}?include[]=node&include[]=nest&include[]=egg&include[]=allocations&include[]=user&include[]=features");
        return $data['attributes'];
    }

    /**
     * Suspend server
     */
    public function Suspend() {
        $id = $this->account_details['extra_details']['device_id'] ?? null;
        if (!$id) { $this->addError('Missing device_id'); return false; }
        $this->api("servers/{$id}/suspension",'POST',['suspended'=>true]);
        return in_array($this->response_code,[200,204]);
    }

    /**
     * Unsuspend server
     */
    public function Unsuspend() {
        $id = $this->account_details['extra_details']['device_id'];
        $this->api("servers/{$id}/suspension",'POST',['suspended'=>false]);
        return in_array($this->response_code,[200,204]);
    }

    /**
     * Reinstall server
     */
    public function Reinstall() {
        $id = $this->account_details['extra_details']['device_id'];
        $this->api("servers/{$id}/reinstall",'POST');
        return in_array($this->response_code,[200,204]);
    }

    /**
     * Rebuild server
     */
    public function Rebuild() {
        $id = $this->account_details['extra_details']['device_id'];
        $this->api("servers/{$id}/rebuild",'POST');
        return in_array($this->response_code,[200,204]);
    }

    /**
     * Terminate server
     */
    public function Terminate() {
        $id = $this->account_details['extra_details']['device_id'];
        $this->api("servers/{$id}",'DELETE');
        return in_array($this->response_code,[200,204]);
    }

    /**
     * Change package (resources)
     */
    public function ChangePackage() { /* unchanged */ }

    /**
     * Helper: primary allocation
     */
    public function getPrimaryAllocation($allocations) {
        foreach ($allocations as $a) {
            if ($a['attributes']['primary']) return $a['attributes']['id'];
        }
        return false;
    }

    /**
     * Sync fields callback
     */
    public function changeFormsFields($account_config) {
        if (empty($account_config)) return true;
        $this->setAccountConfig(array_merge($this->account_config,$account_config));
        return $this->ChangePackage();
    }

    /**
     * Panel login URL
     */
    public function getPanelLoginUrl() { return $this->_parseHostname() . '/login'; }

    /**
     * Synchronize info
     */
    public function getSynchInfo() { /* unchanged */ }

    /**
     * List product servers
     */
    public function getProductServers($product_id) { /* unchanged */ }

    /**
     * List accounts
     */
    public function getAccounts() { /* unchanged */ }

    /**
     * Import type
     */
    public function getImportType() { return ImportAccounts_Model::TYPE_IMPORT_NO_PRODUCTS; }

    /**
     * Error handler
     */
    public function addError($message) {
        $this->logger()->error('WISP :: Error', ['message' => $message]);
        if ($this->fileLoggingEnabled) {
            $this->logToFile('WISP :: Error', $message);
        }
        parent::addError($message);
    }

    /**
     * Parse egg variables
     */
    private function parseVariables($variables, $nodeAndAllocations, $data) { /* unchanged */ }

    /**
     * Write to file helper
     */
    private function logToFile($label, $data) {
        $line = "[" . date('Y-m-d H:i:s') . "] $label:\n" . print_r($data,true) . "\n";
        file_put_contents('/tmp/wispgg.log',$line,FILE_APPEND);
    }
}
