/**
 *
 * Thank you for Eric Shepherd (a2sheppy) for the original sample code:
 * https://github.com/a2sheppy/mdn-samples/tree/master/s/webrtc-capturestill
 *
 * We have slightly modified Eric's code here.
 *
 */
function Scanner()
{
  /**
   * The width and height of the captured photo. We will set the
   * width to the value defined here, but the height will be
   * calculated based on the aspect ratio of the input stream.
   */
  var width = 250;    // We will scale the photo width to this
  var height = 0;     // This will be computed based on the input stream

  /**
   * |streaming| indicates whether or not we're currently streaming
   * video from the camera. Obviously, we start at false.
   */
  var streaming = false;

  /**
   * The various HTML elements we need to configure or control. These
   * will be set by the init() function.
   */
  var video = null;
  var canvas = null;
  var photo = null;
  var stream = null;
  var dataUrl = null;

  this.start = function()
  {
    video = document.getElementById('video');
    canvas = document.getElementById('canvas');
    photo = document.getElementById('photo');

    navigator.getMedia = ( navigator.getUserMedia ||
                           navigator.webkitGetUserMedia ||
                           navigator.mozGetUserMedia ||
                           navigator.msGetUserMedia);

    navigator.getMedia(
    {
      video: true,
      audio: false
    },
    function(_stream)
    {
      stream = _stream;
      if (navigator.mozGetUserMedia)
      {
        video.mozSrcObject = stream;
      }
      else
      {
        var vendorURL = window.URL || window.webkitURL;
        video.src = vendorURL.createObjectURL(stream);
      }
      video.play();
    },
    function(err)
    {
      console.log('Scanner: an error occured! ' + err);
    });

    video.addEventListener('canplay', function(e)
    {
      var self = e.target;
      if (! streaming)
      {
        height = self.videoHeight / (self.videoWidth / width);
        /**
         * Firefox currently has a bug where the height can't be read from
         * the video, so we will make assumptions if this happens.
         */
        if (isNaN(height))
        {
          height = width / (4/3);
        }

        self.setAttribute('width', width);
        self.setAttribute('height', height);
        canvas.setAttribute('width', 0);
        canvas.setAttribute('height', 0);

        streaming = true;
      }
    }, false);

    this.clearPhoto();
  }

  /**
   * Stops the scanner
   */
  this.stop = function()
  {
    if (video && stream)
    {
      video.pause();
      video.src= '';
      stream.stop();

      video.setAttribute('width', 0);
      video.setAttribute('height', 0);

      canvas.setAttribute('width', 0);
      canvas.setAttribute('height', 0);

      streaming = false;
      console.log('stopped');
    }
  }

  /**
   * Fill the photo with an indication that none has been captured.
   */
  this.clearPhoto = function()
  {
    var context = canvas.getContext('2d');
    context.fillStyle = "#AAA";
    context.fillRect(0, 0, canvas.width, canvas.height);
  }

  /**
   * Capture a photo by fetching the current contents of the video
   * and drawing it into a canvas.
   */
  this.takePicture = function()
  {
    var context = canvas.getContext('2d');

    if (video && width && height)
    {
      canvas.width = width;
      canvas.height = height;
      context.drawImage(video, 0, 0, width, height);
      dataUrl = canvas.toDataURL('image/png');
    }
    else
    {
      this.clearPhoto();
    }
  }

  /**
   * returns the video object
   */
  this.getVideo = function()
  {
    return video;
  }

  /**
   * returns the canvas object
   */
  this.getCanvas = function()
  {
    return canvas;
  }

  /**
   * returns the data URL of the taken image
   */
  this.getDataUrl = function()
  {
    return dataUrl;
  }

  return this;
}
