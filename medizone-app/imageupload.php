<?php

include 'abc_db_connection.php';

require 'vendor/autoload.php';
	
use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;

if (isset($_POST["submit"]))
{

	// AWS Info
    $bucketName = 'project-abc-files';
    // Use the IAM users credentials
    $IAM_KEY = 'ADD KEY HERE';
    $IAM_SECRET = 'ADD S KEY HERE';

	// Connect to AWS
	try {
		// You may need to change the region. It will say in the URL when the bucket is open
		// and on creation.
		$s3 = S3Client::factory(
			array(
				'credentials' => array(
					'key' => $IAM_KEY,
					'secret' => $IAM_SECRET
				),
				'version' => 'latest',
				'region'  => 'us-west-2'
			)
		);
	} catch (Exception $e) {
		// We use a die, so if this fails. It stops here. Typically this is a REST call so this would
		// return a json object.
		die("Error: " . $e->getMessage());
	}

	
	// For this, I would generate a unqiue random string for the key name. But you can do whatever.
	$keyName = 'test_example/' . basename($_FILES["fileToUpload"]['name']);
	$pathInS3 = 'https://s3.ap-us-west-2.amazonaws.com/' . $bucketName . '/' . $keyName;

	// Add it to S3
	try {
		// Uploaded:
		$file = $_FILES["fileToUpload"]['tmp_name'];

		$s3->putObject(
			array(
				'Bucket'=>$bucketName,
				'Key' =>  $keyName,
				'SourceFile' => $file,
				'StorageClass' => 'REDUCED_REDUNDANCY'
			)
		);

	} catch (S3Exception $e) {
		die('Error:' . $e->getMessage());
	} catch (Exception $e) {
		die('Error:' . $e->getMessage());
	}


	echo 'Done';


//To upload the metadata to the database...

    $dockey = $_POST["rkey"];
    $did = $_POST["did"];
    $pid = $_POST["pid"];
    $docType = $_POST["dtype"];
    $comment = $_POST["comm"];

    $fileName=$_FILES["fileToUpload"]["name"];
    $fileType=$_FILES["fileToUpload"]["type"];
    $fileSize=$_FILES["fileToUpload"]["size"];

    //$target_path = "uploads/";
    //$target_path = $target_path.basename($_FILES['fileToUpload']['name']);


        date_default_timezone_set('Australia/Sydney');
        $uptime = date("Y-m-d H:i:s");

        $qry = "INSERT INTO document_table (
            doc_key,
            doctor_id,
            patient_id,
            doc_type,
            comment,
            file_name,
            file_type,
            file_size,
            s_time
        )
        values
        (
            '$dockey',
            '$did',
            '$pid',
            '$docType',
            '$comment',
            '$fileName',
            '$fileType',
            '$fileSize',
            '$uptime'
        )";

        if(!mysqli_query($conn, $qry))
        {
            echo "Error uploading data to database.";
            
        }
        else 
        {
            echo "Record successfully added to ABC Image Service Database";
        }
}
?>
