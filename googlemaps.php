<!DOCTYPE html >
  <head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
    <meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
    <title>Using MySQL and PHP with Google Maps</title>
    <style>
      /* Always set the map height explicitly to define the size of the div
       * element that contains the map. */
      #map {
        height: 100%;
      }
      /* Optional: Makes the sample page fill the window. */
      html, body {
        height: 100%;
        margin: 0;
        padding: 0;
      }
    </style>
  </head>

  <body>
  
    <div id="map"></div>

    <script>
	   var customLabel = {
        1: {
          label: '1'
        },
        2: {
          label: '2'
        },
		3: {
          label: '3'
        },
        4: {
          label: '4'
        },
		5: {
          label: '5'
        },
        6: {
          label: '6'
        },
		7: {
          label: '7'
        },
        8: {
          label: '8'
        },
		9: {
          label: '9'
        },
        10: {
          label: '10'
        },
		11: {
          label: '11'
        },
        12: {
          label: '12'
        },
		13: {
          label: '13'
        },
        14: {
          label: '14'
        },
		15: {
          label: '15'
        },
        16: {
          label: '16'
        },
		17: {
          label: '17'
        },
        18: {
          label: '18'
		},
        19: {
          label: '19'
        },
        20: {
          label: '20'
        },
        21: {
          label: '21'
        },
        22: {
          label: '22'
        },
        23: {
          label: '23'
        },
        24: {
          label: '24'
        },
        25: {
          label: '25'
        },
        26: {
          label: '26'
        }
		
      };


        function initMap() {
        var map = new google.maps.Map(document.getElementById('map'), {
          center: new google.maps.LatLng(30.5252, -84.3321),
          zoom: 5
        });
        var infoWindow = new google.maps.InfoWindow;

          // Change this depending on the name of your PHP or XML file
         downloadUrl('http://18.188.132.78/xmltest.php', function(data) {
            var xml = data.responseXML;
            var markers = xml.documentElement.getElementsByTagName('marker');
            Array.prototype.forEach.call(markers, function(markerElem) {
              var hop = markerElem.getAttribute('hop');
              var ip = markerElem.getAttribute('ip');
              var ms = markerElem.getAttribute('ms');
              var point = new google.maps.LatLng(
                  parseFloat(markerElem.getAttribute('lat')),
                  parseFloat(markerElem.getAttribute('lng')));

              var infowincontent = document.createElement('div');
              var strong = document.createElement('strong');
              strong.textContent = point
              infowincontent.appendChild(strong);
              infowincontent.appendChild(document.createElement('br'));

              var text = document.createElement('text');
              text.textContent = ip
              infowincontent.appendChild(text);
              var icon = customLabel[hop] || {};
              var marker = new google.maps.Marker({
                map: map,
				draggable:true,
                position: point,
                label: icon.label
              });
              marker.addListener('click', function() {
                infoWindow.setContent(infowincontent);
                infoWindow.open(map, marker);
              });
            });
          });
        }



      function downloadUrl(url, callback) {
        var request = window.ActiveXObject ?
            new ActiveXObject('Microsoft.XMLHTTP') :
            new XMLHttpRequest;

        request.onreadystatechange = function() {
          if (request.readyState == 4) {
            request.onreadystatechange = doNothing;
            callback(request, request.status);
          }
        };

        request.open('GET', url, true);
        request.send(null);
      }

      function doNothing() {}
    </script>
    <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBxQGs_844TfxfCPtKtP8fQo69QmP-uwEo&callback=initMap">
    </script>
  </body>
</html>