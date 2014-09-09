<script language="Javascript">
	<!--
	//Preloaded slideshow script- By Jason Moon
//For this script and more
//Visit http://www.dynamicdrive.com

// PUT THE URL'S OF YOUR IMAGES INTO THIS ARRAY...

var SlideReady = true;
var CurrentSlide = 0;
var Slides = new Array(<{assign var="i" value=0}><{foreach item=img from=$images}><{if $i>0}>,'<{$storedir}>ths/<{$img}>'<{else}>'<{$storedir}>ths/<{$img}>'<{/if}><{assign var="i" value=$i+1}><{/foreach}>);

// DO NOT EDIT BELOW THIS LINE!
function CacheImage(ImageSource) { // TURNS THE STRING INTO AN IMAGE OBJECT
   var ImageObject = new Image();
   ImageObject.src = ImageSource;
   return ImageObject;
}

function ShowSlide(Direction) {
   if (SlideReady) {
      NextSlide = CurrentSlide + Direction;
      // THIS WILL DISABLE THE BUTTONS (IE-ONLY)
      document.SlideShow.Previous.disabled = (NextSlide == 0);
      document.SlideShow.Next.disabled = (NextSlide == 
(Slides.length-1));    
 if ((NextSlide >= 0) && (NextSlide < Slides.length)) {
            document.images['Screen'].src = Slides[NextSlide].src;
            CurrentSlide = NextSlide++;
            Message = 'Picture ' + (CurrentSlide+1) + ' of ' + 
Slides.length;
            self.defaultStatus = Message;
            if (Direction == 1) CacheNextSlide();
      }
      return true;
   }
}

function Download() {
   if (Slides[NextSlide].complete) {
      SlideReady = true;
      self.defaultStatus = Message;
   }
   else setTimeout("Download()", 100); // CHECKS DOWNLOAD STATUS EVERY 100 MS
   return true;
}

function CacheNextSlide() {
   if ((NextSlide < Slides.length) && (typeof Slides[NextSlide] == 
'string'))
{ // ONLY CACHES THE IMAGES ONCE
      SlideReady = false;
      self.defaultStatus = 'Downloading next picture...';
      Slides[NextSlide] = CacheImage(Slides[NextSlide]);
      Download();
   }
   return true;
}

function StartSlideShow() {
   CurrentSlide = -1;
   Slides[0] = CacheImage(Slides[0]);
   SlideReady = true;
   ShowSlide(1);
}
	-->
</script>