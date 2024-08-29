<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Navigation Map</title>
    <link rel="stylesheet" href="./CSS_FILE/navigationmap.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <!-- <h1 class="header">Navigation Map</h1>
        <div class="map">
            <div class="divbox">1</div>
            <div class="divbox">2</div>
            <div class="divbox">3</div>
            <div class="divbox">4</div>
            <div class="divbox">5</div>
            <div class="divbox">6</div>
            <div class="divbox">7</div>
            <div class="divbox">8</div>
            <div class="divbox">9</div>
            <div class="divbox">10</div>  
            <div class="divbox">11</div>
            <div class="divbox">12</div>
            <div class="divbox">13</div>
            <div class="divbox">14</div>
            <div class="divbox">15</div>
            <div class="divbox">16</div>
            <div class="divbox">17</div>
            <div class="divbox">18</div>
            <div class="divbox">19</div>
            <div class="divbox">20</div>  
        </div> -->

        <!-- <header><h1 class="header">Navigation Map</h1></header> -->
        <div class="row">
                <div class="col-3 aside">
                   <img src="./images/bogomemoriallogo.png" alt="Memorial Park Logo">
                   <a href="#"><i class="fa fa-map"></i> Memorial Park Map</a>
                    <a href="#" onclick="showContent('div1')"><i class="fa fa-map-marker-alt"></i> Block 1</a>
                    <a href="#" onclick="showContent('div2')"><i class="fa fa-map-marker-alt"></i> Block 2</a>
                    <a href="#" onclick="showContent('div3')"><i class="fa fa-map-marker-alt"></i> Block 3</a>
                    <a href="#"><i class="fa fa-map-marker-alt"></i> Block 4</a>
                    <a href="#"><i class="fa fa-compass"></i> Navigation</a>
                    <a href="#"><i class="fa fa-tachometer-alt"></i> Dashboard</a> 
                    <a href="#"><i class="fa fa-sign-out-alt"></i> Logout</a>  
                </div>
            <!-- <div class="col-6 header">Navigation Map</div> -->
            <div class="col-9 map">
                <h1>Navigation Map</h1>
                <br>
                <div class=" col-12  map-detail" id="div1">
                   <div class="divbox">1</div>
                   <div class="divbox">2</div>
                   <div class="divbox">3</div>
                   <div class="divbox">4</div>
                   <div class="divbox">5</div>
                   <div class="divbox">6</div>
                   <div class="divbox">7</div>
                   <div class="divbox">8</div>
                   <div class="divbox">9</div>
                   <div class="divbox">10</div>
                   <div class="divbox">11</div>
                   <div class="divbox">12</div>
                   <div class="divbox">13</div>
                   <div class="divbox">14</div>
                   <div class="divbox">15</div>
                   <div class="divbox">16</div>
                   <div class="divbox">17</div>
                   <div class="divbox">18</div>
                   <div class="divbox">19</div>
                   <div class="divbox">20</div>
                   <div class="divbox">21</div>
                   <div class="divbox">22</div>
                   <div class="divbox">23</div>
                   <div class="divbox">24</div>
                   <div class="divbox">25</div>
                   <div class="divbox">26</div>
                   <div class="divbox">27</div>
                   <div class="divbox">28</div>
                   <div class="divbox">29</div>
                   <div class="divbox">30</div>
                   <div class="divbox">31</div>
                   <div class="divbox">32</div>
                   <div class="divbox">33</div>
                   <div class="divbox">34</div>
                   <div class="divbox">35</div>
                   <div class="divbox">36</div>
                   <div class="divbox">37</div>
                   <div class="divbox">38</div>
                   <div class="divbox">39</div>
                   <div class="divbox">40</div>
                   <div class="divbox">41</div>
                   <div class="divbox">42</div>
                   <div class="divbox">43</div>
                   <div class="divbox">44</div>
                   <div class="divbox">45</div>
                   <div class="divbox">46</div>
                   <div class="divbox">47</div>
                   <div class="divbox">48</div>
                   <div class="divbox">49</div>
                   <div class="divbox">50</div>
                </div>
                <div id="div2" class="map-detail">div2</div>
                <div id="div3" class="map-detail">div3</div>
            </div>
        </div>
        <script>
        function showContent(divId) {
            // Hide all divs
            var contents = document.getElementsByClassName('content');
            for (var i = 0; i < contents.length; i++) {
                contents[i].style.display = 'none';
            }

            // Show the selected div
            document.getElementById(divId).style.display = 'block';
        }
    </script>

</body>
</html>