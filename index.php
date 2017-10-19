<!DOCTYPE html>
<html lang="en">
<head>
	<title>Get URL Details</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <!-- Bootsrap CSS, jQuery, Tether, Bootsrap JS CDNs -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
<body>
  <!-- Using full width container for NavBar -->
<div class="container-fluid px-0">
  <nav class="navbar navbar-inverse bg-primary">
    <div class="navbar-brand mx-auto">URL Details</div>
  </nav>
</div>
<div class="container">
	<div class="col-md-9 mx-auto mt-5" >
        <!-- Form to take input parameters from the user -->
        <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
          <div class="form-group">
            <label class="col-form-label" for="name">Enter a URL</label>
            <input type="text" class="form-control" name="url" value="" placeholder="http://example.com">
          </div>
          <!-- This div used to alert user of errors or success message -->
          <div class="text-center">
            <button type="submit" class="btn btn-primary" name="send">Get Details</button>
            <button type="reset" class="btn btn-danger ml-md-5">Cancel</button>
          </div>
        </form>
  </div>
  </div>
</body>
</html>
<?php
if(isset($_POST["url"])){
  if(empty($_POST["url"])){
    echo "<br>Please enter a URL<br>";
  }
  else{
    $url = $_POST["url"];
    $ch = curl_init(); //initializing cURL and setting operations
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

    $data = curl_exec($ch);
    if (!curl_errno($ch)) { //check whether cURL has some error
      $details = curl_getinfo($ch); //get cURL request details, an associative array is returned if specific cURL
                                      //CONSTANT isn't supplied
      curl_close($ch); //close cURL
        //getting the cURL details
      echo "Load Time: ". $details['total_time'] , "<br>";
      echo "HTTP Code: ". $details['http_code'] , "<br>";
      echo "Primary IP: ". $details['primary_ip'] , "<br>";
      echo "URLs: ". $details['redirect_url'] , "<br>";

      $doc = new DOMDocument(); //instance of DOMDocument
      @$doc->loadHTML($data); //parsing the HTML page
      $nodes = $doc->getElementsByTagName('title');
      $title = $nodes->item(0)->nodeValue;
      $metas = $doc->getElementsByTagName('meta'); //metas contains meta tags
      for ($i = 0; $i < $metas->length; $i++)
        {
          $meta = $metas->item($i); //access the items in the metas array
          if($meta->getAttribute('name') == 'description' || $meta->getAttribute('name') == 'Description'){
            $description = $meta->getAttribute('content');
          }
          if($meta->getAttribute('name') == 'keywords' || $meta->getAttribute('name') == 'Keywords'){
            $keywords = $meta->getAttribute('content');
          }
        }
      echo "<br/><br/>Title: $title ". '<br/><br/>';
      //some websites doesn't have meta tags like google.co.in
      if(!empty($description)){
        echo "Description: $description". '<br/><br/>';
      }else{
        echo "No Description found";
      }
      if(!empty($keywords)){
        echo "Description: $keywords". '<br/><br/>';
      }else{
        echo "<br/><br/>No Keywords found";
      }
    }
  }
}
 ?>
