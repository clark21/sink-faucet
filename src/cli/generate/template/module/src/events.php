<?php //-->
/**
 * This file is part of the Cradle PHP Kitchen Sink Faucet Project.
 * (c) 2016-2018 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

use Cradle\Module\{{namespace}}\Service as {{camel name 1}}Service;
use Cradle\Module\{{namespace}}\Validator as {{camel name 1}}Validator;

{{~#if has_file}}

use Cradle\Module\Utility\File;
{{~/if}}

/**
 * {{camel name 1}} Create Job
 *
 * @param Request $request
 * @param Response $response
 */
$cradle->on('{{name}}-create', function ($request, $response) {
    //----------------------------//
    // 1. Get Data
    $data = [];
    if ($request->hasStage()) {
        $data = $request->getStage();
    }

    //----------------------------//
    // 2. Validate Data
    $errors = {{camel name 1}}Validator::getCreateErrors($data);

    //if there are errors
    if (!empty($errors)) {
        return $response
            ->setError(true, 'Invalid Parameters')
            ->set('json', 'validation', $errors);
    }

    //----------------------------//
    // 3. Prepare Data
    {{~#each fields}}
        {{~#when form.inline_type '===' 'image-field'}}

    //if there is an image
    if (isset($data['{{@key}}'])) {
        //upload files
        //try cdn if enabled
        $config = $this->package('global')->service('s3-main');
        $data['{{@key}}'] = File::base64ToS3($data['{{@key}}'], $config);
        //try being old school
        $upload = $this->package('global')->path('upload');
        $data['{{@key}}'] = File::base64ToUpload($data['{{@key}}'], $upload);
    }
        {{~/when}}

        {{~#when form.inline_type '===' 'images-field'}}

    //if there is an image
    if (isset($data['{{@key}}'])) {
        //upload files
        //try cdn if enabled
        $config = $this->package('global')->service('s3-main');
        $data['{{@key}}'] = File::base64ToS3($data['{{@key}}'], $config);
        //try being old school
        $upload = $this->package('global')->path('upload');
        $data['{{@key}}'] = File::base64ToUpload($data['{{@key}}'], $upload);
    }
        {{~/when}}

        {{~#when form.inline_type '===' 'file-field'}}

    //if there is an image
    if (isset($data['{{@key}}'])) {
        //upload files
        //try cdn if enabled
        $config = $this->package('global')->service('s3-main');
        $data['{{@key}}'] = File::base64ToS3($data['{{@key}}'], $config);
        //try being old school
        $upload = $this->package('global')->path('upload');
        $data['{{@key}}'] = File::base64ToUpload($data['{{@key}}'], $upload);
    }
        {{~/when}}

        {{~#when form.inline_type '===' 'files-field'}}

    //if there is an image
    if (isset($data['{{@key}}'])) {
        //upload files
        //try cdn if enabled
        $config = $this->package('global')->service('s3-main');
        $data['{{@key}}'] = File::base64ToS3($data['{{@key}}'], $config);
        //try being old school
        $upload = $this->package('global')->path('upload');
        $data['{{@key}}'] = File::base64ToUpload($data['{{@key}}'], $upload);
    }
        {{~/when}}

        {{~#when sql.encoding '===' 'json'}}

    if(isset($data['{{@key}}'])) {
        $data['{{@key}}'] = json_encode($data['{{@key}}']);
    }
        {{~/when}}

        {{~#when sql.encoding '===' 'date'}}

    if(isset($data['{{@key}}'])) {
        $data['{{@key}}'] = date('Y-m-d', strtotime($data['{{@key}}']));
    }
        {{~/when}}

        {{~#when sql.encoding '===' 'time'}}

    if(isset($data['{{@key}}'])) {
        $data['{{@key}}'] = date('H:i:s', strtotime($data['{{@key}}']));
    }
        {{~/when}}

        {{~#when sql.encoding '===' 'datetime'}}

    if(isset($data['{{@key}}'])) {
        $data['{{@key}}'] = date('Y-m-d H:i:s', strtotime($data['{{@key}}']));
    }
        {{~/when}}

        {{~#when sql.encoding '===' 'md5'}}

    if(isset($data['{{@key}}'])) {
        $data['{{@key}}'] = md5(strtotime($data['{{@key}}']));
    }
        {{~/when}}

        {{~#when sql.encoding '===' 'sha1'}}

    if(isset($data['{{@key}}'])) {
        $data['{{@key}}'] = sha1(strtotime($data['{{@key}}']));
    }
        {{~/when}}

        {{~#when sql.encoding '===' 'bool'}}

    if(isset($data['{{@key}}'])) {
        $data['{{@key}}'] = 0;
        if($data['{{@key}}']) {
            $data['{{@key}}'] = 1;
        }
    }
        {{~/when}}

        {{~#when sql.encoding '===' 'token'}}

    $data['{{@key}}'] = md5(uniqid());
        {{~/when}}

        {{~#when sql.encoding '===' 'created'}}

    $data['{{@key}}'] = date('Y-m-d H:i:s');
        {{~/when}}

        {{~#when sql.encoding '===' 'uuid'}}

    $data['{{@key}}'] = md5(uniqid());
        {{~/when}}

        {{~#when sql.encoding '===' 'updated'}}

    $data['{{@key}}'] = date('Y-m-d H:i:s');
        {{~/when}}

        {{~#when sql.encoding '===' 'inline'}}

    {{sql.code_create}}
    {{sql.code}}
        {{~/when}}
    {{~/each}}

    //----------------------------//
    // 4. Process Data
    //this/these will be used a lot
    ${{name}}Sql = {{camel name 1}}Service::get('sql');
    ${{name}}Redis = {{camel name 1}}Service::get('redis');
    ${{name}}Elastic = {{camel name 1}}Service::get('elastic');

    //save {{name}} to database
    $results = ${{name}}Sql->create($data);

    {{~#each relations}}
    //link {{name}}
    if(isset($data['{{primary}}'])) {
        ${{../name}}Sql->link{{camel name 1}}($results['{{../primary}}'], $data['{{primary}}']);
    }
    {{~/each}}

    //index {{name}}
    ${{name}}Elastic->create($results['{{primary}}']);

    //invalidate cache
    ${{name}}Redis->removeSearch();

    //return response format
    $response->setError(false)->setResults($results);
});

/**
 * {{camel name 1}} Detail Job
 *
 * @param Request $request
 * @param Response $response
 */
$cradle->on('{{name}}-detail', function ($request, $response) {
    //----------------------------//
    // 1. Get Data
    $data = [];
    if ($request->hasStage()) {
        $data = $request->getStage();
    }

    $id = null;
    if (isset($data['{{primary}}'])) {
        $id = $data['{{primary}}'];
    {{#if unique.length}}{{#each unique~}}
    } else if (isset($data['{{this}}'])) {
        $id = $data['{{this}}'];
    {{/each}}{{/if~}}
    }

    //----------------------------//
    // 2. Validate Data
    //we need an id
    if (!$id) {
        return $response->setError(true, 'Invalid ID');
    }

    //----------------------------//
    // 3. Prepare Data
    //no preparation needed
    //----------------------------//
    // 4. Process Data
    //this/these will be used a lot
    ${{name}}Sql = {{camel name 1}}Service::get('sql');
    ${{name}}Redis = {{camel name 1}}Service::get('redis');
    ${{name}}Elastic = {{camel name 1}}Service::get('elastic');

    $results = null;

    //if no flag
    if (!$request->hasGet('nocache')) {
        //get it from cache
        $results = ${{name}}Redis->getDetail($id);
    }

    //if no results
    if (!$results) {
        //if no flag
        if (!$request->hasGet('noindex')) {
            //get it from index
            $results = ${{name}}Elastic->get($id);
        }

        //if no results
        if (!$results) {
            //get it from database
            $results = ${{name}}Sql->get($id);
        }

        if ($results) {
            //cache it from database or index
            ${{name}}Redis->createDetail($id, $results);
        }
    }

    if (!$results) {
        return $response->setError(true, 'Not Found');
    }

    {{~#each relations}}{{#when @key '===' 'profile'}}

    //if permission is provided
    $permission = $request->getStage('permission');
    if ($permission && $results['profile_id'] != $permission) {
        return $response->setError(true, 'Invalid Permissions');
    }
    {{~/when}}{{/each}}

    $response->setError(false)->setResults($results);
});

/**
 * {{camel name 1}} Remove Job
 *
 * @param Request $request
 * @param Response $response
 */
$cradle->on('{{name}}-remove', function ($request, $response) {
    //----------------------------//
    // 1. Get Data
    //get the {{name}} detail
    $this->trigger('{{name}}-detail', $request, $response);

    //----------------------------//
    // 2. Validate Data
    if ($response->isError()) {
        return;
    }

    //----------------------------//
    // 3. Prepare Data
    $data = $response->getResults();

    //----------------------------//
    // 4. Process Data
    //this/these will be used a lot
    ${{name}}Sql = {{camel name 1}}Service::get('sql');
    ${{name}}Redis = {{camel name 1}}Service::get('redis');
    ${{name}}Elastic = {{camel name 1}}Service::get('elastic');

    {{~#if active}}

    //save to database
    $results = ${{name}}Sql->update([
        '{{primary}}' => $data['{{primary}}'],
        '{{active}}' => 0
    ]);
    {{~else}}

    //remove from database
    $results = ${{name}}Sql->remove($data['{{primary}}']);
    {{~/if}}

    //remove from index
    ${{name}}Elastic->remove($data['{{primary}}']);

    //invalidate cache
    ${{name}}Redis->removeDetail($data['{{primary}}']);
    {{#if unique.length}}{{#each unique~}}
    ${{../name}}Redis->removeDetail($data['{{this}}']);
    {{/each}}{{/if~}}

    ${{name}}Redis->removeSearch();

    $response->setError(false)->setResults($results);
});

{{~#if active}}

/**
 * {{camel name 1}} Restore Job
 *
 * @param Request $request
 * @param Response $response
 */
$cradle->on('{{name}}-restore', function ($request, $response) {
    //----------------------------//
    // 1. Get Data
    //get the {{name}} detail
    $this->trigger('{{name}}-detail', $request, $response);

    //----------------------------//
    // 2. Validate Data
    if ($response->isError()) {
        return;
    }

    //----------------------------//
    // 3. Prepare Data
    $data = $response->getResults();

    //----------------------------//
    // 4. Process Data
    //this/these will be used a lot
    ${{name}}Sql = {{camel name 1}}Service::get('sql');
    ${{name}}Redis = {{camel name 1}}Service::get('redis');
    ${{name}}Elastic = {{camel name 1}}Service::get('elastic');

    //save to database
    $results = ${{name}}Sql->update([
        '{{primary}}' => $data['{{primary}}'],
        '{{active}}' => 1
    ]);

    //create index
    ${{name}}Elastic->create($data['{{primary}}']);

    //invalidate cache
    ${{name}}Redis->removeSearch();

    $response->setError(false)->setResults($results);
});
{{~/if}}

/**
 * {{camel name 1}} Search Job
 *
 * @param Request $request
 * @param Response $response
 */
$cradle->on('{{name}}-search', function ($request, $response) {
    //----------------------------//
    // 1. Get Data
    $data = [];
    if ($request->hasStage()) {
        $data = $request->getStage();
    }

    //----------------------------//
    // 2. Validate Data
    //no validation needed
    //----------------------------//
    // 3. Prepare Data
    //no preparation needed
    //----------------------------//
    // 4. Process Data
    //this/these will be used a lot
    ${{name}}Sql = {{camel name 1}}Service::get('sql');
    ${{name}}Redis = {{camel name 1}}Service::get('redis');
    ${{name}}Elastic = {{camel name 1}}Service::get('elastic');

    $results = false;

    //if no flag
    if (!$request->hasGet('nocache')) {
        //get it from cache
        $results = ${{name}}Redis->getSearch($data);
    }

    //if no results
    if (!$results) {
        //if no flag
        if (!$request->hasGet('noindex')) {
            //get it from index
            $results = ${{name}}Elastic->search($data);
        }

        //if no results
        if (!$results) {
            //get it from database
            $results = ${{name}}Sql->search($data);
        }

        if ($results) {
            //cache it from database or index
            ${{name}}Redis->createSearch($data, $results);
        }
    }

    //set response format
    $response->setError(false)->setResults($results);
});

/**
 * {{camel name 1}} Update Job
 *
 * @param Request $request
 * @param Response $response
 */
$cradle->on('{{name}}-update', function ($request, $response) {
    //----------------------------//
    // 1. Get Data
    //get the {{name}} detail
    $this->trigger('{{name}}-detail', $request, $response);

    //if there's an error
    if ($response->isError()) {
        return;
    }

    //get data from stage
    $data = [];
    if ($request->hasStage()) {
        $data = $request->getStage();
    }

    //----------------------------//
    // 2. Validate Data
    $errors = {{camel name 1}}Validator::getUpdateErrors($data);

    //if there are errors
    if (!empty($errors)) {
        return $response
            ->setError(true, 'Invalid Parameters')
            ->set('json', 'validation', $errors);
    }

    //----------------------------//
    // 3. Prepare Data
    {{~#each fields}}
        {{~#when form.inline_type '===' 'image-field'}}

    //if there is an image
    if (isset($data['{{@key}}'])) {
        //upload files
        //try cdn if enabled
        $config = $this->package('global')->service('s3-main');
        $data['{{@key}}'] = File::base64ToS3($data['{{@key}}'], $config);
        //try being old school
        $upload = $this->package('global')->path('upload');
        $data['{{@key}}'] = File::base64ToUpload($data['{{@key}}'], $upload);
    }
        {{~/when}}

        {{~#when form.inline_type '===' 'images-field'}}

    //if there is an image
    if (isset($data['{{@key}}'])) {
        //upload files
        //try cdn if enabled
        $config = $this->package('global')->service('s3-main');
        $data['{{@key}}'] = File::base64ToS3($data['{{@key}}'], $config);
        //try being old school
        $upload = $this->package('global')->path('upload');
        $data['{{@key}}'] = File::base64ToUpload($data['{{@key}}'], $upload);
    }
        {{~/when}}

        {{~#when form.inline_type '===' 'images-field'}}

    //if there is an image
    if (isset($data['{{@key}}'])) {
        //upload files
        //try cdn if enabled
        $config = $this->package('global')->service('s3-main');
        $data['{{@key}}'] = File::base64ToS3($data['{{@key}}'], $config);
        //try being old school
        $upload = $this->package('global')->path('upload');
        $data['{{@key}}'] = File::base64ToUpload($data['{{@key}}'], $upload);
    }
        {{~/when}}

        {{~#when form.inline_type '===' 'file-field'}}

    //if there is an image
    if (isset($data['{{@key}}'])) {
        //upload files
        //try cdn if enabled
        $config = $this->package('global')->service('s3-main');
        $data['{{@key}}'] = File::base64ToS3($data['{{@key}}'], $config);
        //try being old school
        $upload = $this->package('global')->path('upload');
        $data['{{@key}}'] = File::base64ToUpload($data['{{@key}}'], $upload);
    }
        {{~/when}}

        {{~#when form.inline_type '===' 'files-field'}}

    //if there is an image
    if (isset($data['{{@key}}'])) {
        //upload files
        //try cdn if enabled
        $config = $this->package('global')->service('s3-main');
        $data['{{@key}}'] = File::base64ToS3($data['{{@key}}'], $config);
        //try being old school
        $upload = $this->package('global')->path('upload');
        $data['{{@key}}'] = File::base64ToUpload($data['{{@key}}'], $upload);
    }
        {{~/when}}

        {{~#when sql.encoding '===' 'json'}}

    if(isset($data['{{@key}}'])) {
        $data['{{@key}}'] = json_encode($data['{{@key}}']);
    }
        {{~/when}}

        {{~#when sql.encoding '===' 'date'}}

    if(isset($data['{{@key}}'])) {
        $data['{{@key}}'] = date('Y-m-d', strtotime($data['{{@key}}']));
    }
        {{~/when}}

        {{~#when sql.encoding '===' 'time'}}

    if(isset($data['{{@key}}'])) {
        $data['{{@key}}'] = date('H:i:s', strtotime($data['{{@key}}']));
    }
        {{~/when}}

        {{~#when sql.encoding '===' 'datetime'}}

    if(isset($data['{{@key}}'])) {
        $data['{{@key}}'] = date('Y-m-d H:i:s', strtotime($data['{{@key}}']));
    }
        {{~/when}}

        {{~#when sql.encoding '===' 'md5'}}

    if(isset($data['{{@key}}'])) {
        $data['{{@key}}'] = md5(strtotime($data['{{@key}}']));
    }
        {{~/when}}

        {{~#when sql.encoding '===' 'sha1'}}

    if(isset($data['{{@key}}'])) {
        $data['{{@key}}'] = sha1(strtotime($data['{{@key}}']));
    }
        {{~/when}}

        {{~#when sql.encoding '===' 'bool'}}

    if(isset($data['{{@key}}'])) {
        $data['{{@key}}'] = 0;
        if($data['{{@key}}']) {
            $data['{{@key}}'] = 1;
        }
    }
        {{~/when}}

        {{~#when sql.encoding '===' 'uuid'}}

    $data['{{@key}}'] = md5(uniqid());
        {{~/when}}

        {{~#when sql.encoding '===' 'updated'}}

    $data['{{@key}}'] = date('Y-m-d H:i:s');
        {{~/when}}

        {{~#when sql.encoding '===' 'inline'}}

    {{sql.code_update}}
    {{sql.code}}
        {{~/when}}
    {{~/each}}

    //----------------------------//
    // 4. Process Data
    //this/these will be used a lot
    ${{name}}Sql = {{camel name 1}}Service::get('sql');
    ${{name}}Redis = {{camel name 1}}Service::get('redis');
    ${{name}}Elastic = {{camel name 1}}Service::get('elastic');

    //save {{name}} to database
    $results = ${{name}}Sql->update($data);

    //index {{name}}
    ${{name}}Elastic->update($response->getResults('{{primary}}'));

    //invalidate cache
    ${{name}}Redis->removeDetail($response->getResults('{{primary}}'));
    {{#if unique.length}}{{#each unique~}}
    ${{../name}}Redis->removeDetail($data['{{this}}']);
    {{/each}}{{/if~}}
    ${{name}}Redis->removeSearch();

    //return response format
    $response->setError(false)->setResults($results);
});

{{~#each relations}}
    {{~#when name '===' ../name}}

/**
 * Links {{../singular}} to {{name}}
 *
 * @param Request $request
 * @param Response $response
 */
$cradle->on('{{../name}}-link-{{name}}', function ($request, $response) {
    //----------------------------//
    // 1. Get Data
    $data = [];
    if ($request->hasStage()) {
        $data = $request->getStage();
    }

    //----------------------------//
    // 2. Validate Data
    if (!isset($data['{{../primary}}_1'], $data['{{primary}}_2'])) {
        return $response->setError(true, 'No ID provided');
    }

    //----------------------------//
    // 3. Process Data
    //this/these will be used a lot
    ${{../name}}Sql = {{camel ../name 1}}Service::get('sql');
    ${{../name}}Redis = {{camel ../name 1}}Service::get('redis');
    ${{../name}}Elastic = {{camel ../name 1}}Service::get('elastic');

    $results = ${{../name}}Sql->link{{camel name 1}}(
        $data['{{../primary}}_1'],
        $data['{{primary}}_2']
    );

    //index post
    ${{../name}}Elastic->update($data['{{../primary}}_1']);

    //invalidate cache
    ${{../name}}Redis->removeSearch();

    //return response format
    $response->setError(false)->setResults($results);
});

/**
 * Unlinks {{../singular}} from {{name}}
 *
 * @param Request $request
 * @param Response $response
 */
$cradle->on('{{../name}}-unlink-{{name}}', function ($request, $response) {
    //----------------------------//
    // 1. Get Data
    $data = [];
    if ($request->hasStage()) {
        $data = $request->getStage();
    }

    //----------------------------//
    // 2. Validate Data
    if (!isset($data['{{../primary}}_1'], $data['{{primary}}_2'])) {
        return $response->setError(true, 'No ID provided');
    }

    //----------------------------//
    // 3. Process Data
    //this/these will be used a lot
    ${{../name}}Sql = {{camel ../name 1}}Service::get('sql');
    ${{../name}}Redis = {{camel ../name 1}}Service::get('redis');
    ${{../name}}Elastic = {{camel ../name 1}}Service::get('elastic');

    $results = ${{../name}}Sql->unlink{{camel name 1}}(
        $data['{{../primary}}_1'],
        $data['{{primary}}_2']
    );

    //index post
    ${{../name}}Elastic->update($data['{{../primary}}_1']);

    //invalidate cache
    ${{../name}}Redis->removeSearch();

    //return response format
    $response->setError(false)->setResults($results);
});

    {{~else}}

/**
 * Links {{../singular}} to {{name}}
 *
 * @param Request $request
 * @param Response $response
 */
$cradle->on('{{../name}}-link-{{name}}', function ($request, $response) {
    //----------------------------//
    // 1. Get Data
    $data = [];
    if ($request->hasStage()) {
        $data = $request->getStage();
    }

    //----------------------------//
    // 2. Validate Data
    if (!isset($data['{{../primary}}'], $data['{{primary}}'])) {
        return $response->setError(true, 'No ID provided');
    }

    //----------------------------//
    // 3. Process Data
    //this/these will be used a lot
    ${{../name}}Sql = {{camel ../name 1}}Service::get('sql');
    ${{../name}}Redis = {{camel ../name 1}}Service::get('redis');
    ${{../name}}Elastic = {{camel ../name 1}}Service::get('elastic');

    $results = ${{../name}}Sql->link{{camel name 1}}(
        $data['{{../primary}}'],
        $data['{{primary}}']
    );

    //index post
    ${{../name}}Elastic->update($data['{{../primary}}']);

    //invalidate cache
    ${{../name}}Redis->removeSearch();

    //return response format
    $response->setError(false)->setResults($results);
});

/**
 * Unlinks {{../singular}} from {{name}}
 *
 * @param Request $request
 * @param Response $response
 */
$cradle->on('{{../name}}-unlink-{{name}}', function ($request, $response) {
    //----------------------------//
    // 1. Get Data
    $data = [];
    if ($request->hasStage()) {
        $data = $request->getStage();
    }

    //----------------------------//
    // 2. Validate Data
    if (!isset($data['{{../primary}}'], $data['{{primary}}'])) {
        return $response->setError(true, 'No ID provided');
    }

    //----------------------------//
    // 3. Process Data
    //this/these will be used a lot
    ${{../name}}Sql = {{camel ../name 1}}Service::get('sql');
    ${{../name}}Redis = {{camel ../name 1}}Service::get('redis');
    ${{../name}}Elastic = {{camel ../name 1}}Service::get('elastic');

    $results = ${{../name}}Sql->unlink{{camel name 1}}(
        $data['{{../primary}}'],
        $data['{{primary}}']
    );

    //index post
    ${{../name}}Elastic->update($data['{{../primary}}']);

    //invalidate cache
    ${{../name}}Redis->removeSearch();

    //return response format
    $response->setError(false)->setResults($results);
});
    {{~/when}}
    {{~#if many}}

/**
 * Unlinks all {{../singular}} from {{name}}
 *
 * @param Request $request
 * @param Response $response
 */
$cradle->on('{{../name}}-unlinkall-{{name}}', function ($request, $response) {
    //----------------------------//
    // 1. Get Data
    $data = [];
    if ($request->hasStage()) {
        $data = $request->getStage();
    }

    //----------------------------//
    // 2. Validate Data
    if (!isset($data['{{../primary}}'])) {
        return $response->setError(true, 'No ID provided');
    }

    //----------------------------//
    // 3. Process Data
    //this/these will be used a lot
    ${{../name}}Sql = {{camel ../name 1}}Service::get('sql');
    ${{../name}}Redis = {{camel ../name 1}}Service::get('redis');
    ${{../name}}Elastic = {{camel ../name 1}}Service::get('elastic');

    $results = ${{../name}}Sql->unlinkAll{{camel name 1}}($data['{{../primary}}']);

    //index post
    ${{../name}}Elastic->update($data['{{../primary}}']);

    //invalidate cache
    ${{../name}}Redis->removeSearch();

    //return response format
    $response->setError(false)->setResults($results);
});
    {{~/if~}}
{{/each}}
