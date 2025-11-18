<!DOCTYPE html>
<html lang="zxx">
<head>
    <?php include 'toplinks.php' ?>
    <title>Thank You | TechMech Cranes |  Hire Cranes Hyderabad wide</title>
    <!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-239918360-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-239918360-1');
</script>

</head>
   <body>
       <?php include 'header.php' ?>
   
      <!--=========Banner start============-->
      <div class="inner-pages-bnr">
         <img src="images/banners/thankyou.webp" onerror="this.onerror=null;this.src='images/banners/thankyou.jpg';" class="img-responsive" alt="contact-banner-image">
         <div class="banner-caption">
            <h1>Thank you</h1>
            <ul class="breadcumb">
               <li><a href="index.php">Home</a> - </li>
               <li>Thank you</li>
            </ul>
         </div>
      </div>
      <!--=========Banner end============-->
  
      <section class="pad100-top-bottom">
      <div class="container">
           	<h2 class="title" style="text-align:center;font-size:80px">Thank You</h2>
           	<p style="text-align: center!important;font-size:30px;margin:25px;">We will get back to you soon..!!!</p>
           	
           	<div class="text-center">
           	    <p>If not downloaded Click below to download</p>
           	    <!-- <a id="downloadBrochure" href="brochure-ckfabrics.pdf" download="brochure-ckfabrics.pdf" class="site-button button-md mb-3">Download Brochure</a> -->
           	</div>
            <div class="text-center" style="margin-bottom:10px;">
                  <a  id="broc"  href="e-Boucher-TECHMECH.pdf" download><button class="btn btn-primary" class="site-button button-md mb-3">Download Brochure</button></a>
               
            </div>
            <div class="mt-4  text-center">
         
                  <a href="index.php"><button class="btn btn-primary">Back to Home</button></a>
            </div>
           <!-- <a href="index.php"><button  class="site-button button-md" style="margin-left:44%;margin-bottom:2%;"> <span>Back to Home</span> </button></a> -->
       </div>   
      
      </section>
    
      <!--=========Footer Start============-->
      
      <!--=========Footer Start============-->
      <?php include 'footer.php' ?>
      <?php
$dwn=$_GET['dwn'];
if($dwn==1)
{
    ?>
    <iframe src="filedwnld.php" style="display:none;"></iframe>
    <?php
    
    
}
?>
   </body>

</html>