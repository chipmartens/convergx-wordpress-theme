/* Hero globe. Turns a statically rendered orthographic plate into a rotating
 * one by rewriting the d attributes of the elements that are already there.
 *
 * Zero dependencies. Nothing is fetched at runtime. Works offline. The land
 * geometry below is Natural Earth 110m admin 0, public domain, simplified and
 * embedded by the build script that also renders the static plate, so the
 * resting frame and this file's frame zero are the same arithmetic.
 *
 * THE POLARITY: this file only ever REWRITES elements the markup already
 * contains. It adds nothing and removes nothing. With scripts disabled the
 * plate in the SVG is the finished figure. If this file fails to load, fails to
 * parse, or throws, the reader still sees a complete globe.
 *
 * Budget: capped frame rate, paused when off screen, paused when the tab is
 * hidden, stopped under prefers-reduced-motion including when that changes at
 * runtime. Every per-point value that does not change between frames is
 * precomputed once. Set window.CX_GLOBE_DEBUG = true before this loads to get
 * per frame cost on the object it exposes.
 */
(function () {
  "use strict";

  var D = {"R":265.0,"CX":1130.0,"CY":350.0,"PHI0":40.0,"EPS":-23.44,"LAM0":-31.0,"ARC_STEPS":24,"LIFT":0.09,"FADE":0.16,"GRAT_LON":30,"GRAT_LAT":[-60,-30,0,30,60],"GRAT_STEP":5,"TURN":210.0,"T0":150.0,"N_DEALS":105,"STAGGER":2.0,"D_IN":1.5,"D_HOLD":5.5,"D_OUT":2.0,"D_LIFE":9.0,"BREATHE":0.2,"MAX_LIVE":5,"MAX_LEGS":5,"SITES":[[49.2827,-123.1207],[51.0447,-114.0719],[49.8951,-97.1384],[43.6532,-79.3832],[45.5019,-73.5674],[61.2181,-149.9003],[47.6062,-122.3321],[39.7392,-104.9903],[29.7604,-95.3698],[42.3601,-71.0589],[64.1466,-21.9426],[55.8642,-4.2518],[54.5973,-5.9301],[53.4808,-2.2426],[69.6492,18.9553],[58.97,5.7331],[65.5842,22.1547],[65.0121,25.4651],[61.4978,23.761],[56.1629,10.2039],[59.437,24.7536],[56.9496,24.1052],[54.8985,23.9036],[54.352,18.6466],[52.4064,16.9252],[53.5511,9.9937],[48.7758,9.1829],[51.9244,4.4777],[51.2194,4.4025],[49.6116,6.1319],[45.764,4.8357],[43.6047,1.4442],[49.1951,16.6068],[48.7164,21.2611],[47.6875,17.6504],[46.5547,15.6459],[45.3271,14.4422],[45.0703,7.6869],[44.4949,11.3426],[43.263,-2.935],[41.6488,-0.8891],[41.1579,-8.6291],[38.7223,-9.1393],[40.6401,22.9444],[42.1354,24.7453],[46.7712,23.6236],[41.3275,19.8187],[42.4304,19.2594],[41.9981,21.4254],[41.0082,28.9784],[39.9334,32.8597],[43.0618,141.3545],[38.2682,140.8694],[35.1815,136.9066],[37.4563,126.7052],[35.1796,129.0756]],"DEALS":[[1,[51,52,53,54,55]],[49,[10,30]],[5,[8,15,17,20]],[12,[3,49]],[1,[16,23]],[34,[2,5,9,15,18]],[5,[11,14,23,42,46]],[22,[6,7,50,52,55]],[5,[19,21,33,42,46]],[53,[17,19]],[25,[2,5,8,16,50]],[2,[4,16,18,46]],[22,[1,6,12,28,49]],[8,[1,16,23]],[23,[2,5,6,8,12]],[9,[17,41,43]],[16,[2,25,45,53]],[3,[15,18,33]],[28,[5,8,21,44,51]],[55,[20,22,25,50]],[15,[3,35,40,55]],[1,[51,52,53,54,55]],[10,[0,9,15,20,25]],[37,[9,14,17]],[10,[6,9,15,55]],[52,[2,15,23,25,49]],[11,[3,39,51]],[49,[5,27,51,52,54]],[10,[11,47,49]],[3,[17,33,46,50]],[18,[5,8]],[5,[46,47]],[18,[9,40,44,54]],[54,[0,15,19,25,26]],[13,[5,43,48,54]],[1,[7,20,34,38]],[28,[44,46,47,51]],[2,[24,47]],[23,[5,8,14,44,54]],[2,[8,13,16,32]],[16,[42,47,53]],[53,[0,18,22]],[1,[51,52,53,54,55]],[0,[3,30,33,41,45]],[28,[10,21,48,50,51]],[51,[44,47]],[23,[7,51]],[2,[12,42]],[11,[3,7,51,54]],[5,[9,19,25,53]],[18,[3,5,30,39]],[7,[19,23,25]],[21,[8,13,26,49,54]],[8,[10,11,24,30,42]],[23,[51,54]],[53,[10,19,23]],[5,[8,20,23,52,54]],[46,[9,14,16]],[14,[39,46,55]],[48,[4,21,25,31,42]],[5,[22,35,41]],[4,[24,30]],[1,[51,52,53,54,55]],[40,[8,21,45,46]],[17,[1,40,51,52,54]],[2,[15,40,44,45]],[28,[1,3]],[2,[15,16,51]],[14,[1,8,37]],[5,[4,26,48,49]],[10,[5,19,22,37,51]],[38,[6,13,16]],[10,[6,7,18,41]],[38,[0,4]],[10,[5,27]],[48,[3,9,13,21,31]],[1,[10,13,20,46]],[50,[9,17,21,53,54]],[14,[6,8,53]],[3,[33,35,43]],[25,[1,5]],[54,[1,15]],[24,[6,31,50,52]],[1,[51,52,53,54,55]],[20,[8,9,46,51]],[8,[13,17,27]],[21,[4,11,12,13]],[51,[2,24]],[24,[0,5,31,41,53]],[5,[13,17,31,38,54]],[13,[3,34,38,50]],[55,[5,18,32]],[17,[2,11,30,32,48]],[50,[26,52]],[10,[21,44,51]],[50,[3,16,36,40]],[14,[31,51,53]],[39,[1,19,24]],[5,[4,27]],[7,[15,27,31,32,42]],[19,[4,7,48,55]],[4,[2,29,42,44]],[16,[50,55]],[6,[24,37,39]],[1,[51,52,53,54,55]]],"LAND":[[[33.9,-0.9,39.2,-4.7,40.3,-10.3,36.5,-11.7,29.6,-6.5,30.4,-1.1,31.9,-1.0]],[[-8.7,27.7,-8.7,25.9,-12.0,25.9,-12.9,21.3,-17.1,21.0,-8.8,27.7]],[[-122.8,49,-127.4,50.8,-130.5,54.3,-130.0,55.9,-135.5,59.8,-141.0,60.3,-141.0,69.7,-128.1,70.5,-113.5,67.7,-106.2,68.8,-96.1,67.3,-94.2,69.1,-96.5,70.1,-95.2,71.9,-87.4,67.2,-85.5,69.9,-81.2,68.7,-81.4,67.1,-85.8,66.6,-93.2,62.0,-94.7,58.9,-92.3,57.1,-82.3,55.1,-79.9,51.2,-78.6,52.6,-79.8,54.7,-76.5,56.5,-78.5,58.8,-78.1,62.3,-69.6,61.1,-67.6,58.2,-64.6,60.3,-55.7,52.1,-60.0,50.2,-66.4,50.2,-71.1,46.8,-65.1,49.2,-64.5,46.2,-59.8,45.9,-66.1,43.6,-64.4,45.3,-67.1,45.1,-69.2,47.4,-71.5,45.0,-82.7,41.7,-82.6,45.3,-88.4,48.3,-120,49],[-79.8,72.8,-80.8,73.7,-78.1,73.7,-76.3,72.8,-79.5,72.7],[-88.2,74.4,-96.7,77.2,-81.1,75.7,-80.5,74.7,-86.1,74.4],[-55.6,51.3,-56.8,49.8,-53.5,49.2,-53.1,46.7,-59.3,47.6,-55.4,51.6],[-83.9,65.1,-80.1,63.7,-87.2,63.5,-84.5,65.4],[-78.8,72.4,-68.8,70.5,-67.0,69.2,-68.8,68.7,-61.9,66.9,-63.9,65.0,-68.0,66.3,-64.7,63.4,-68.8,63.7,-66.2,61.9,-78.6,64.6,-72.7,67.3,-77.3,69.8,-89.5,70.8,-90.2,72.2,-88.4,73.5,-85.8,73.8,-85.8,72.5,-82.3,73.8,-80.7,72.1],[-94.5,74.1,-90.5,73.9,-94.3,72.0,-95.5,73.9],[-121.5,74.4,-115.5,73.5,-123.1,70.9,-125.9,71.9,-123.9,73.7,-124.9,74.3],[-107.8,75.8,-106.3,75.0,-112.2,74.4,-117.7,75.2,-108.2,76.2],[-106.5,73.1,-101.1,69.6,-113.3,68.5,-117.3,70.0,-112.4,70.4,-117.9,70.5,-116.1,71.3,-118.6,72.3,-109.9,73.0,-108.2,71.7,-107.5,73.2],[-100.4,72.7,-97.4,73.8,-96.7,71.7,-98.4,71.3,-102.5,72.8],[-98.5,76.7,-98.2,75,-102.6,76.3,-98.6,76.6],[-96.0,80.6,-92.4,81.3,-85.8,79.3,-92.9,78.3,-96.7,80.2],[-91.6,81.9,-61.9,82.6,-76.9,79.3,-75.4,78.5,-80.6,76.2,-89.5,76.5,-88.3,77.9,-85.0,77.5,-88.0,78.4,-85.1,79.3,-86.9,80.3,-81.8,80.5,-91.4,81.6]],[[-122.8,49,-88.4,48.3,-82.6,45.3,-82.7,41.7,-67.8,47.1,-67.0,44.8,-70.1,43.7,-70.0,41.6,-74.0,40.8,-75.9,37.2,-76.3,39.1,-75.7,35.6,-81.3,31.4,-80.7,25.1,-84.1,30.1,-94.7,29.5,-97.5,25.8,-106.5,31.8,-117.1,32.5,-120.6,34.6,-124.4,40.3,-124.7,48.2,-122.6,47.1,-122.5,48.2]],[[87.4,49.2,80.0,44.9,80.3,42.3,74.2,43.3,68.3,40.7,64.9,43.7,58.5,45.6,55.9,45.0,56.0,41.3,52.5,41.8,50.3,44.6,53.0,45.3,53.0,46.9,49.1,46.4,46.5,48.4,50.8,51.7,61.3,50.8,60.0,52.0,61.4,54.0,69.1,55.4,73.4,53.5,76.9,54.5,80.0,50.9,86.8,49.8]],[[56.0,41.3,55.9,45.0,58.5,45.6,64.9,43.7,66.7,41.2,71.0,42.3,73.1,40.9,67.7,39.6,67.8,37.1,58.6,42.8,57.1,41.3]],[[141.0,-2.6,147.6,-6.1,147.2,-7.4,150.8,-10.3,144.7,-7.6,141.0,-9.1,141.0,-5.9],[151.3,-5.8,148.4,-5.4,152.1,-4.1,151.5,-5.6]],[[141.0,-2.6,141.0,-9.1,137.6,-8.4,137.9,-5.4,133.0,-4.1,132.0,-2.8,133.7,-2.2,130.5,-0.9,134.0,-0.8,135.5,-3.4,137.4,-1.7,139.9,-2.4],[117.9,4.1,119.0,0.9,116.1,-4.0,112.1,-3.5,110.2,-2.9,109.1,1.3,114.6,1.4,117.0,4.3],[122.9,0.9,125.1,1.6,124.4,0.4,120.0,-0.5,123.3,-0.6,121.5,-1.9,123.2,-5.3,121.0,-2.6,119.8,-5.7,118.8,-2.8,120.0,0.6,121.7,1.0],[108.5,-6.4,115.7,-8.4,106.5,-7.4,106.1,-5.9,108.1,-6.3],[104.4,-1.1,106.1,-3.1,104.7,-5.9,95.3,5.5,101.7,2.1,104.0,-1.1]],[[-57.6,-30.2,-58.5,-34.4,-56.8,-36.9,-65.1,-41.1,-63.5,-42.6,-67.3,-45.6,-65.6,-47.2,-69.1,-50.7,-68.1,-52.3,-71.9,-52.0,-73.4,-49.3,-71.2,-44.8,-70.5,-31.4,-66.3,-21.8,-62.8,-22.0,-57.8,-25.2,-58.6,-27.1,-55.7,-27.4,-54.1,-25.5,-53.6,-26.9,-56.3,-28.9]],[[-68.6,-52.6,-68.6,-54.9,-67.0,-54.9,-68.6,-55.6,-74.7,-52.8,-71.1,-54.1,-69.3,-52.5],[-69.6,-17.6,-67.0,-23.0,-70.5,-31.4,-71.2,-44.8,-73.4,-49.3,-71.9,-52.0,-68.6,-52.3,-72.6,-53.5,-74.9,-52.3,-75.6,-48.7,-72.7,-42.4,-74.3,-43.2,-69.9,-18.1]],[[29.3,-4.5,30.7,-8.3,28.7,-8.5,28.4,-11.8,29.7,-13.3,22.2,-11.1,21.7,-7.3,17.5,-8.1,16.3,-5.9,12.2,-5.8,15.8,-3.9,19.5,5.0,29.7,4.6,31.2,2.2,29.3,-3.3]],[[41.6,-1.7,41.0,2.8,51.1,12.0,48.6,5.3,41.8,-1.4]],[[39.2,-4.7,33.9,-0.9,34.0,4.2,35.3,5.5,38.1,3.6,41.9,3.9,41.6,-1.7,39.6,-4.3]],[[24.6,8.2,21.9,12.6,25,22,36.9,22,38.4,18.0,34.0,8.7,32.7,12.2,31.4,9.8,25.1,10.3,23.9,8.6]],[[23.8,19.6,23.9,15.6,21.9,12.6,22.9,11.1,15.3,7.4,13.5,14.4,15.9,20.4,14.9,22.9,19.8,21.5]],[[-71.7,18.0,-71.6,19.9,-68.3,18.6,-71.7,17.8]],[[49.1,46.4,46.7,44.6,47.8,41.2,40.0,43.4,36.7,45.2,40.1,49.6,31.8,52.1,32.7,53.4,30.9,55.6,28.2,56.2,28.1,60.5,31.5,62.9,28.4,68.4,32.1,69.9,41.1,67.5,38.4,66.0,33.2,66.6,37.0,63.8,37.2,65.1,43.9,66.1,43.5,68.6,46.2,68.2,46.3,66.7,53.7,68.9,59.9,68.3,60.5,69.8,68.5,68.1,66.7,71.0,72.6,72.8,71.8,71.4,73.7,68.4,72.4,66.2,75.1,67.8,73.1,71.4,74.7,72.8,75.3,71.3,81.5,71.8,80.5,73.6,104.4,77.7,114.1,75.8,109.4,74.2,127.0,73.6,131.3,70.8,139.9,71.5,139.1,72.4,140.5,72.8,159.0,70.9,160.9,69.4,180,69.0,180,65.0,177.4,64.6,179.2,62.3,163.5,59.9,162.0,58.2,163.1,56.2,156.8,51.0,155.9,56.8,164.5,62.6,160.1,60.5,156.7,61.4,155.0,59.1,142.2,59.0,135.1,54.7,141.3,53.1,140.1,48.4,134.9,43.4,130.8,42.2,131.0,45.0,133.1,45.1,135.0,48.5,131.0,47.8,123.6,53.5,120.2,52.8,117.9,49.5,108.5,49.3,98.9,52.0,97.3,49.7,92.2,50.8,87.4,49.2,80.0,50.9,76.9,54.5,73.4,53.5,69.1,55.4,61.4,54.0,60.0,52.0,61.3,50.8,50.8,51.7,47.5,50.5,46.5,48.4,48.6,46.6],[102.8,79.3,105.1,78.3,99.4,77.9,102.1,79.3],[53.5,73.7,68.2,76.9,58.5,74.3,55.4,72.4,57.5,70.7,51.6,71.5,54.4,73.6],[142.9,53.7,144.7,49.0,143.2,49.3,143.5,46.1,142.1,46.0,142.7,54.4],[-174.9,67.2,-169.9,66.0,-173.0,64.3,-178.7,66.1,-180,65.0,-180,69.0,-177.6,68.2],[33.4,46.0,36.5,45.5,33.3,44.6,33.6,45.9]],[[15.1,79.7,21.5,79.0,17.1,76.8,11.2,78.9,13.7,79.7],[31.1,69.6,18.0,68.6,12.6,64.1,11.0,58.9,5.7,58.6,5.0,62.0,16.4,68.6,24.5,71.0,30.0,70.2]],[[-46.8,82.6,-27.1,83.5,-20.8,82.7,-31.4,82.0,-12.2,81.3,-20.0,80.2,-17.7,80.1,-19.7,78.8,-18.5,77.0,-21.7,76.6,-19.4,74.3,-24.8,72.3,-21.8,70.7,-26.4,70.2,-22.3,70.1,-39.8,65.5,-44.8,60.0,-51.6,63.6,-54.0,67.2,-50.9,69.9,-54.7,69.6,-51.4,70.6,-55.8,71.7,-54.7,72.6,-57.3,74.7,-73.3,78.0,-65.7,79.4,-68.0,80.1,-62.7,81.8,-46.9,82.2]],[[16.3,-28.6,19.9,-28.5,19.9,-24.8,21.6,-26.7,31.2,-22.3,30.7,-26.7,32.8,-26.7,32.5,-28.3,27.5,-33.2,19.6,-34.8,17.1,-29.9]],[[-117.1,32.5,-106.5,31.8,-103.9,29.3,-101.0,29.4,-97.1,25.9,-97.9,22.4,-94.4,18.1,-86.8,21.3,-87.8,18.3,-91.5,17.3,-90.4,16.4,-92.2,14.5,-98.0,16.1,-105.0,19.3,-106.0,22.8,-113.1,31.2,-114.8,31.8,-114.7,30.2,-110.0,22.8,-116.7,31.6]],[[-57.6,-30.2,-53.8,-32.0,-53.4,-33.8,-57.8,-34.5,-57.9,-31.0]],[[-53.4,-33.8,-53.8,-32.0,-57.6,-30.2,-53.6,-26.1,-55.8,-22.4,-57.9,-22.1,-58.2,-16.3,-65.4,-11.6,-65.3,-9.8,-70.1,-11.1,-70.5,-9.5,-73.2,-9.5,-72.9,-5.3,-69.9,-4.3,-69.8,1.7,-65.5,0.8,-63.4,2.2,-64.8,4.1,-60.7,5.2,-59.0,1.3,-52.9,2.1,-51.3,4.2,-50.7,0.2,-48.6,-1.2,-35.2,-5.5,-35.1,-9.0,-38.7,-13.1,-40.9,-21.9,-47.6,-24.9,-52.7,-33.2]],[[-69.5,-11.0,-65.3,-9.8,-65.4,-11.6,-58.2,-16.3,-57.9,-20.0,-61.8,-19.6,-62.7,-22.2,-67.8,-22.9,-69.6,-17.6,-68.7,-12.6]],[[-69.9,-4.3,-74.0,-7.5,-68.7,-12.6,-69.6,-17.6,-76.0,-14.6,-81.1,-4.0,-79.2,-5.0,-75.1,-0.1,-70.0,-2.7,-70.4,-3.8]],[[-66.9,1.3,-69.8,1.7,-69.9,-4.3,-70.0,-2.7,-79.0,1.7,-77.1,3.8,-77.4,8.7,-71.8,12.4,-73.3,9.2,-72.0,7.0,-67.3,6.1,-67.2,2.3]],[[-77.4,8.7,-77.9,7.2,-79.1,9.0,-80.4,7.3,-82.9,8.1,-82.5,9.6,-77.7,8.9]],[[-82.5,9.6,-83.0,8.2,-85.7,9.9,-85.6,11.2,-83.0,10.0]],[[-83.7,10.9,-87.7,12.9,-83.1,15.0,-83.8,11.1]],[[-83.1,15.0,-87.0,13.0,-89.4,14.4,-87.9,15.9,-83.4,15.3]],[[-92.2,14.5,-90.4,16.4,-91.5,17.3,-89.1,17.8,-88.2,15.7,-91.7,14.1]],[[-60.7,5.2,-64.8,4.1,-63.4,2.2,-66.3,0.7,-67.3,6.1,-72.4,7.4,-72.9,10.5,-71.3,11.8,-71.3,9.1,-69.9,12.2,-68.2,10.6,-61.9,10.7,-59.8,8.4,-61.4,6.0]],[[-56.5,1.9,-59.6,1.8,-61.4,6.0,-59.8,8.4,-57.1,6.0,-57.2,2.8]],[[-54.5,2.3,-56.5,1.9,-57.9,4.8,-54.0,5.8,-54.3,2.7]],[[-51.7,4.2,-54.5,2.3,-54.0,5.8,-51.8,4.6],[6.2,49.5,8.1,49.0,6.0,46.7,7.5,44.1,6.5,43.1,-1.5,43.0,-1.2,46.0,-4.6,48.7,2.5,51.1,5.9,49.4]],[[-75.4,-0.2,-78.6,-4.5,-80.4,-4.4,-80.1,0.8,-75.8,0.1]],[[-82.3,23.2,-74.3,20.1,-77.8,19.9,-81.8,22.6,-85.0,21.9,-82.5,23.1]],[[31.2,-22.3,28.0,-21.5,25.3,-17.7,29.5,-15.6,32.3,-16.4,32.2,-21.1]],[[29.4,-22.1,25.7,-25.5,20.9,-26.8,20.9,-18.3,25.3,-17.7,28.8,-21.6]],[[19.9,-24.8,19.9,-28.5,16.3,-28.6,11.7,-17.3,25.1,-17.6,20.9,-18.3,19.9,-21.8]],[[-16.7,13.6,-17.6,14.7,-15.1,16.6,-11.5,12.4,-16.7,12.4,-13.8,13.5,-15.6,13.6]],[[-11.5,12.4,-11.7,15.4,-5.5,15.5,-6.5,25.0,4.3,19.2,3.6,15.6,-4.0,13.5,-5.8,10.2,-11.5,12.1]],[[-17.1,21.0,-12.9,21.3,-12.0,25.9,-8.7,25.9,-8.7,27.4,-4.9,25.0,-6.5,25.0,-5.5,15.5,-12.2,14.6,-16.5,16.1,-16.5,20.6]],[[2.7,6.3,0.8,10.5,3.6,11.7,2.7,7.9]],[[14.9,22.9,15.9,20.4,14.2,12.5,5.4,13.9,3.6,11.7,0.3,14.4,3.6,15.6,4.3,19.2,12.0,23.5,14.1,22.5]],[[2.7,6.3,3.7,12.6,5.4,13.9,13.3,13.6,14.6,12.1,8.5,4.8,5.9,4.3,3.6,6.3]],[[14.5,12.9,15.9,1.7,9.6,2.3,8.5,4.5,11.7,7.0,14.2,12.8]],[[0.0,11.0,1.1,5.9,-2.0,4.7,-2.9,11.0,-0.4,11.1]],[[-8.0,10.2,-2.8,9.6,-2.9,5.0,-7.7,4.4,-8.2,10.1]],[[-13.7,12.6,-9.1,12.3,-7.8,8.6,-9.2,7.3,-11.1,10.0,-13.2,8.9,-15.1,11.0,-13.7,12.2]],[[-16.7,12.4,-13.7,12.6,-15.1,11.0,-16.6,12.2]],[[-8.4,7.7,-7.7,4.4,-11.4,6.8,-10.2,8.4,-8.7,7.7]],[[-13.2,8.9,-11.1,10.0,-10.2,8.4,-11.4,6.8,-13.1,8.2]],[[-5.4,10.4,-4.0,13.5,0.4,14.9,1.9,11.6,-5.0,10.2]],[[27.4,5.2,19.5,5.0,16.0,2.3,14.5,4.7,15.3,7.4,22.9,11.1,27.2,5.6]],[[18.5,3.5,15.8,-3.9,11.9,-5.0,11.5,-2.8,14.3,-2.0,13.1,2.3,15.9,1.7,17.8,3.6]],[[11.3,2.3,14.3,1.2,14.4,-1.3,11.1,-4.0,8.8,-0.8,11.3,1.1]],[[30.7,-8.3,33.2,-9.7,33.2,-14.0,27.0,-17.9,23.2,-17.5,21.9,-12.9,24.0,-12.9,23.9,-10.9,29.7,-13.3,28.4,-9.2,30.3,-8.2]],[[32.8,-9.2,35.7,-14.6,34.4,-16.2,32.7,-13.7,33.2,-9.7]],[[34.6,-11.5,40.3,-10.3,40.8,-14.7,34.8,-19.8,35.5,-24.1,32.1,-26.7,31.2,-22.3,32.8,-16.7,30.2,-14.8,33.2,-14.0,35.0,-16.8,34.3,-12.3]],[[12.3,-6.1,16.3,-5.9,17.5,-8.1,21.7,-7.3,22.2,-11.1,24.0,-11.2,24.0,-12.9,21.9,-12.9,23.2,-17.5,11.7,-17.3,13.7,-11.3,12.2,-6.3]],[[49.5,-12.5,50.5,-15.2,47.1,-24.9,44.0,-25.0,44.4,-16.2,49.2,-12.0]],[[9.5,30.3,7.6,33.3,8.4,36.9,11.1,36.9,10.0,30.5]],[[-8.7,27.4,-8.7,28.8,-1.3,32.3,-2.2,35.2,8.4,36.9,7.5,34.1,9.9,29.0,9.3,26.1,12.0,23.5,3.2,19.1,-4.9,25.0]],[[35.5,32.4,38.8,33.4,39.2,32.2,36.1,29.2,34.9,29.5,35.5,31.8]],[[51.6,24.2,56.1,26.1,56.4,24.9,55.0,22.5,51.6,24.0]],[[39.2,32.2,41.3,36.4,44.8,37.2,48.6,29.9,44.7,29.2,40.4,31.9]],[[55.2,22.7,56.4,24.9,59.8,22.3,57.7,18.9,53.1,16.7,52.0,19.0,55.0,20.0,55.7,22.0]],[[102.6,12.2,103.0,14.2,107.4,14.2,106.2,11.0,103.1,11.2]],[[105.2,14.3,103.0,14.2,102.6,12.2,100.1,13.4,99.2,9.2,101.8,5.8,98.3,7.8,99.6,11.9,97.4,18.4,98.3,19.7,101.3,19.5,101.1,17.5,104.0,18.2,105.5,14.7]],[[107.4,14.2,105.2,14.3,104.0,18.2,101.1,17.5,100.1,20.4,102.2,22.5,107.6,15.2]],[[100.1,20.4,97.4,18.4,99.6,11.9,98.6,9.9,97.2,16.9,94.2,16.0,92.4,20.7,93.3,24.1,97.9,28.3,97.6,23.9,101.2,21.8,100.3,20.8]],[[104.3,10.5,107.5,12.3,107.6,15.2,102.2,22.5,106.7,22.8,108.1,21.6,105.7,19.1,108.9,15.3,109.2,11.7,105.2,8.6,105.1,9.9]],[[130.6,42.4,127.5,39.8,128.2,38.4,124.7,38.1,124.3,39.9,130.0,43.0]],[[126.2,37.7,128.3,38.6,129.1,35.1,126.5,34.4,126.9,36.9]],[[87.8,49.3,92.2,50.8,97.3,49.7,98.9,52.0,108.5,49.3,116.7,49.9,115.7,47.7,119.8,47.0,105.0,41.6,96.3,42.7,88.0,48.6]],[[97.3,28.3,92.7,22.0,91.2,23.5,92.4,25.0,88.6,26.4,88.9,21.7,80.3,15.9,79.9,10.4,77.5,8.0,72.6,21.4,70.5,20.9,68.2,23.7,71.0,24.4,69.5,26.9,75.3,32.3,73.7,34.3,77.8,35.5,78.7,31.5,81.1,30.2,80.1,28.8,85.3,26.7,88.1,26.4,88.7,28.1,92.0,26.8,94.6,29.3,96.2,28.4]],[[92.7,22.0,92.4,20.7,91.4,22.8,89.0,22.1,88.6,26.4,92.4,25.0,92.1,23.6]],[[88.1,27.9,88.1,26.4,83.3,27.4,80.1,28.8,81.5,30.4,87.0,28.0]],[[77.8,35.5,73.7,34.3,75.3,32.3,69.5,26.9,71.0,24.4,61.5,25.1,63.3,26.8,60.9,29.8,66.3,29.9,71.8,36.5,76.2,35.9]],[[66.5,37.4,70.8,38.5,71.8,36.7,75.2,37.1,71.8,36.5,69.3,31.9,64.1,29.3,60.9,29.8,60.5,33.7,61.2,35.7,66.2,37.4]],[[67.8,37.1,67.7,39.6,70.7,41.0,69.5,39.5,73.7,39.4,75.0,37.4,71.8,36.7,70.8,38.5,68.1,37.0]],[[71.0,42.3,80.3,42.3,73.7,39.4,69.5,39.5,73.1,40.9,71.3,42.2]],[[52.5,41.8,57.1,41.3,58.6,42.8,66.5,38.0,62.2,35.3,57.3,38.0,53.9,37.2,52.7,40.0,54.7,41.0,52.8,41.1]],[[48.6,29.9,45.4,34.0,44.1,39.4,48.1,39.6,52.3,36.7,57.3,38.0,61.1,36.5,60.9,29.8,63.3,26.8,61.5,25.1,57.4,25.7,48.9,30.3]],[[35.7,32.7,36.7,36.8,42.3,37.2,41.0,34.4,36.8,32.3]],[[11.0,58.9,11.9,63.1,16.8,68.0,20.6,69.1,23.5,67.9,23.9,66.0,17.8,62.7,17.1,61.3,18.8,60.1,15.9,56.1,12.9,55.4,11.8,57.4]],[[28.2,56.2,32.7,53.4,30.6,51.3,23.5,51.6,23.5,53.9,27.1,55.8]],[[31.8,52.1,40.1,49.6,39.7,47.9,35.0,45.7,31.7,46.7,28.7,45.3,30.0,46.4,28.7,48.1,22.1,48.4,23.5,51.6,30.9,52.0]],[[23.5,53.9,22.8,49.0,15.0,51.1,14.8,54.1,23.2,54.2]],[[17.0,48.1,14.6,46.4,9.5,47.1,16.9,48.5]],[[22.1,48.4,18.5,45.8,16.2,46.9,17.0,48.1,21.9,48.3]],[[26.6,48.2,29.9,46.7,28.2,45.5,26.9,48.1]],[[28.2,45.5,29.6,45.3,28.6,43.7,24.1,43.7,20.2,46.1,26.6,48.2,28.1,45.9]],[[26.5,55.6,23.5,53.9,21.1,56.0,25.5,56.1]],[[27.3,57.5,28.2,56.2,26.5,55.6,21.1,56.0,22.5,57.8,26.5,57.5]],[[14.1,53.8,15.0,51.1,12.2,50.3,12.9,47.5,7.5,47.6,8.1,49.0,6.0,50.1,6.9,53.5,8.5,55.0,13.6,54.1]],[[22.7,44.2,28.6,43.7,28.0,42.0,23.0,41.3,22.4,44.0]],[[23.0,41.3,26.6,41.6,22.6,40.3,23.2,36.4,20.2,39.3,22.8,41.3]],[[26.1,41.8,29.0,41.3,26.4,40.2,26.6,41.6]],[[21.0,40.8,20.0,39.7,19.3,42.2,20.6,41.1]],[[16.6,46.5,19.4,45.2,15.8,44.8,18.5,42.5,13.7,45.1,15.8,46.2]],[[9.6,47.5,10.4,46.5,7.3,45.8,6.0,46.7,8.5,47.8]],[[6.2,50.8,4.3,49.9,2.5,51.1,5.6,51.0]],[[6.9,53.5,6.2,50.8,3.3,51.3,6.1,53.5]],[[-9.0,41.9,-6.4,41.4,-7.9,36.8,-9.5,38.7,-9.0,41.5]],[[-7.5,37.1,-6.4,41.4,-9.4,43.0,3.0,42.5,-2.1,36.7,-6.5,36.9]],[[-6.2,53.9,-6.8,52.3,-10.0,51.8,-9.7,53.9,-7.0,54.1]],[[176.9,-40.1,174.7,-41.3,174.7,-37.4,172.6,-34.5,176.0,-37.6,178.5,-37.7,177.0,-39.9],[169.7,-43.6,172.8,-40.5,174.2,-41.8,169.3,-46.6,166.5,-45.9,168.9,-43.9]],[[147.7,-40.8,146.9,-43.6,144.7,-41.2,146.9,-41.0],[126.1,-32.2,118.0,-35.1,115.0,-34.2,115.7,-31.6,113.3,-26.1,114.1,-21.8,120.9,-19.7,125.7,-14.2,129.6,-15.0,132.4,-11.1,136.5,-11.9,135.5,-15.0,140.2,-17.7,142.5,-10.7,146.4,-19.0,152.9,-25.3,153.1,-30.9,150.0,-37.4,143.6,-38.8,140.6,-38.0,138.2,-34.4,136.8,-35.3,137.8,-32.9,136.0,-34.9,131.3,-31.5,127.1,-32.3]],[[81.8,7.5,79.9,6.8,80.1,9.8,81.3,8.6]],[[80.3,42.3,80.0,44.9,87.8,49.3,90.9,45.3,96.3,42.7,109.2,42.5,111.9,45.1,119.7,46.7,115.5,48.1,119.3,50.1,120.2,52.8,125.1,53.2,131.0,47.8,135.0,48.5,133.1,45.1,131.0,45.0,130.6,42.4,117.5,38.7,122.5,36.9,119.2,34.9,122.1,29.8,118.7,24.5,110.4,20.3,105.3,23.4,101.3,21.2,97.6,23.9,98.7,27.5,96.1,29.5,88.8,27.3,81.1,30.2,73.7,39.4,80.1,42.1]],[[121.8,24.4,120.7,22.0,120.1,23.6,122.0,25.0]],[[10.4,46.9,13.8,46.5,12.6,44.1,18.5,40.2,16.9,40.4,15.7,37.9,15.4,40.0,10.2,43.9,7.4,43.7,6.8,46.0,10.4,46.5],[14.8,38.1,15.1,36.6,12.4,37.6,13.7,38.0]],[[-3.1,53.4,-6.1,56.8,-3.0,58.6,-4.1,57.6,-2.0,57.7,-3.1,56.0,1.7,52.7,1.4,51.3,-5.8,50.2,-3.4,51.4,-5.3,52.0,-4.6,53.5]],[[-14.5,66.5,-13.6,65.1,-18.7,63.5,-24.3,65.6,-16.2,66.5]],[[46.4,41.9,50.4,40.3,48.9,38.3,48.1,39.6,46.5,38.8,45.0,41.2,46.1,41.7]],[[40.0,43.4,46.4,41.9,43.6,41.1,40.3,43.1]],[[126.4,8.4,125.4,5.6,123.6,7.8,121.9,7.2,125.4,9.8,126.3,8.8],[122.3,18.2,121.7,14.3,124.1,12.5,119.9,15.4,120.7,18.5,122.2,18.5]],[[100.1,6.5,103.4,4.9,103.5,1.2,100.3,6.0],[117.9,4.1,115.9,4.3,114.6,1.4,109.8,1.3,115.3,4.3,116.7,6.9,118.6,4.5]],[[28.6,69.1,31.5,62.9,28.1,60.5,22.9,59.8,21.1,62.6,25.4,65.1,20.6,69.1,29.0,69.8]],[[15.0,51.1,18.9,49.5,17.0,48.6,12.5,49.5,14.6,51.0]],[[36.4,14.4,38.4,18.0,43.1,12.7,37.6,14.2]],[[141.9,39.2,140.3,35.1,131.0,33.9,130.7,31.0,129.4,33.3,138.9,37.8,140.3,41.2,141.9,40.0],[144.6,44.0,145.5,43.3,143.2,42.0,140.0,41.6,142.0,45.6,143.9,44.2]],[[-58.2,-20.2,-57.9,-22.1,-54.3,-24.0,-55.7,-27.4,-58.6,-27.1,-57.8,-25.2,-62.7,-22.2,-61.8,-19.6,-58.2,-19.9]],[[52.0,19.0,52.2,15.6,45.0,12.7,43.2,13.2,43.4,17.6,47.0,16.9,49.1,18.6]],[[35.0,29.4,39.2,32.2,48.4,28.6,52.0,23.0,55.2,22.7,55.0,20.0,42.8,16.3,34.8,29.0]],[[-2.2,35.2,-1.3,32.3,-8.7,28.8,-14.8,21.5,-17.0,21.4,-14.4,26.3,-9.6,29.9,-8.7,33.2,-5.9,35.8,-2.6,35.2]],[[36.9,22,25,22,25.2,31.6,34.3,31.2,34.2,27.8,32.3,29.8,36.7,22.2]],[[25,22,23.8,19.6,15.9,23.4,14.1,22.5,9.3,26.1,11.5,33.1,19.1,30.3,20.9,32.7,24.9,31.9,25,25.7]],[[47.8,8.0,41.9,3.9,36.2,4.4,33.0,7.8,37.9,15.0,40.9,14.1,43.3,9.5,46.9,8.0]],[[33.9,-0.9,29.6,-1.3,31.2,3.8,34.5,3.6,33.9,0.1]],[[18.6,42.6,15.8,44.8,19.4,44.9,18.7,43.2]],[[18.8,45.9,22.7,44.6,21.6,42.2,19.2,43.5,19.1,45.5]],[[30.8,3.5,23.9,8.6,25.1,10.3,31.4,9.8,33.2,12.2,33.0,7.8,35.3,5.5,31.2,3.8]]]};

  var RAD = Math.PI / 180;
  var R = D.R, CX = D.CX, CY = D.CY;
  var FPS_CAP = 24;                       // a hero decoration does not need 60

  var roots = [].slice.call(document.querySelectorAll("[data-globe]"));
  if (!roots.length) return;

  /* ---- geometry, all precomputed ------------------------------------- */

  function unit(lat, lon) {
    var p = lat * RAD, l = lon * RAD, c = Math.cos(p);
    return [c * Math.cos(l), c * Math.sin(l), Math.sin(p)];
  }

  function matrix(lam0) {
    var C = Math.cos(lam0 * RAD), S = Math.sin(lam0 * RAD);
    var P = Math.cos(D.PHI0 * RAD), Q = Math.sin(D.PHI0 * RAD);
    var ce = Math.cos(D.EPS * RAD), se = Math.sin(D.EPS * RAD);
    return [-S * ce + Q * C * se, C * ce + Q * S * se, -P * se,
            -S * se - Q * C * ce, C * se - Q * S * ce,  P * ce,
             P * C,               P * S,                Q];
  }

  function mix(a, b, t) {
    var x = a[0] + (b[0] - a[0]) * t,
        y = a[1] + (b[1] - a[1]) * t,
        z = a[2] + (b[2] - a[2]) * t;
    var k = Math.sqrt(x * x + y * y + z * z) || 1;
    return [x / k, y / k, z / k];
  }

  function slerp(a, b, t) {
    var dot = a[0] * b[0] + a[1] * b[1] + a[2] * b[2];
    dot = dot > 1 ? 1 : dot < -1 ? -1 : dot;
    var om = Math.acos(dot);
    if (om < 1e-9) return a.slice();
    var s = Math.sin(om), k1 = Math.sin((1 - t) * om) / s, k2 = Math.sin(t * om) / s;
    return [k1 * a[0] + k2 * b[0], k1 * a[1] + k2 * b[1], k1 * a[2] + k2 * b[2]];
  }

  /* Scratch arrays reused every frame. Allocating per frame is what makes a
   * loop like this stutter on a phone. */
  var sx = [], sy = [], sz = [];

  function projectInto(verts, m, lifts) {
    for (var i = 0; i < verts.length; i++) {
      var v = verts[i], a = v[0], b = v[1], c = v[2];
      var k = lifts ? lifts[i] : 1;
      sx[i] = CX + R * (m[0] * a + m[1] * b + m[2] * c) * k;
      sy[i] = CY - R * (m[3] * a + m[4] * b + m[5] * c) * k;
      sz[i] = m[6] * a + m[7] * b + m[8] * c;
    }
  }

  function project1(v, m) {
    var a = v[0], b = v[1], c = v[2];
    return [CX + R * (m[0] * a + m[1] * b + m[2] * c),
            CY - R * (m[3] * a + m[4] * b + m[5] * c),
            m[6] * a + m[7] * b + m[8] * c];
  }

  var rnd = Math.round;

  /* An open line clipped to the near hemisphere. Each visible run is its own
   * subpath, which is what makes a route travel round the back rather than
   * cut through the sphere. */
  function openPath(verts, m, lifts) {
    projectInto(verts, m, lifts);
    var out = "", run = "", count = 0, i, t, c;
    for (i = 0; i < verts.length; i++) {
      if (sz[i] > 0) {
        if (!count && i) {
          if (sz[i - 1] <= 0) {
            t = sz[i - 1] / (sz[i - 1] - sz[i]);
            c = project1(mix(verts[i - 1], verts[i], t), m);
            run = "M " + rnd(c[0]) + " " + rnd(c[1]);
            count = 1;
          }
        }
        run += (count ? " L " : "M ") + rnd(sx[i]) + " " + rnd(sy[i]);
        count++;
      } else if (count) {
        t = sz[i - 1] / (sz[i - 1] - sz[i]);
        c = project1(mix(verts[i - 1], verts[i], t), m);
        run += " L " + rnd(c[0]) + " " + rnd(c[1]);
        count++;                       /* the crossing is a vertex like any other */
        if (count > 1) out += (out ? " " : "") + run;
        run = ""; count = 0;
      }
    }
    if (count > 1) out += (out ? " " : "") + run;
    return out;
  }

  function limbArc(ex, ey, bx, by) {
    if (Math.abs(ex - bx) < 0.5 && Math.abs(ey - by) < 0.5) return "";
    var ae = Math.atan2(ey - CY, ex - CX), ab = Math.atan2(by - CY, bx - CX);
    var delta = (ab - ae + Math.PI) % (2 * Math.PI) - Math.PI;
    return " A " + rnd(R) + " " + rnd(R) + " 0 0 " + (delta > 0 ? "1" : "0") +
           " " + rnd(bx) + " " + rnd(by);
  }

  /* A filled ring clipped to the near hemisphere, closed along the limb rather
   * than across a chord, so a country cut by the horizon keeps the horizon's
   * curve. */
  function ringPath(verts, m) {
    projectInto(verts, m, null);
    var k = verts.length, runs = [], run = null, i, j, p, t, c;
    for (i = 0; i <= k; i++) {
      j = i % k; p = (i - 1 + k) % k;
      if (sz[j] > 0) {
        if (!run) {
          run = [];
          if (i && sz[p] <= 0) {
            t = sz[p] / (sz[p] - sz[j]);
            c = project1(mix(verts[p], verts[j], t), m);
            run.push(c[0], c[1]);
          }
        }
        if (i < k || !runs.length) run.push(sx[j], sy[j]);
      } else if (run) {
        t = sz[p] / (sz[p] - sz[j]);
        c = project1(mix(verts[p], verts[j], t), m);
        run.push(c[0], c[1]);
        runs.push(run); run = null;
      }
    }
    if (run) runs.push(run);
    if (!runs.length) return "";
    if (runs.length > 1) { runs[0] = runs[runs.length - 1].concat(runs[0]); runs.pop(); }
    var out = "";
    for (i = 0; i < runs.length; i++) {
      var r = runs[i];
      if (r.length < 6) continue;
      var s = "M " + rnd(r[0]) + " " + rnd(r[1]);
      for (j = 2; j < r.length; j += 2) s += " L " + rnd(r[j]) + " " + rnd(r[j + 1]);
      s += limbArc(r[r.length - 2], r[r.length - 1], r[0], r[1]) + " Z";
      out += (out ? " " : "") + s;
    }
    return out;
  }

  /* ---- build the same lines the plate was rendered from ---------------- */

  var grat = [], lat, lon;
  for (lon = -180; lon < 180; lon += D.GRAT_LON) {
    var mer = [];
    for (lat = -90; lat <= 90; lat += D.GRAT_STEP) mer.push(unit(lat, lon));
    grat.push(mer);
  }
  for (var g = 0; g < D.GRAT_LAT.length; g++) {
    var par = [];
    for (lon = -180; lon <= 180; lon += D.GRAT_STEP) par.push(unit(D.GRAT_LAT[g], lon));
    grat.push(par);
  }

  var land = D.LAND.map(function (rings) {
    return rings.map(function (flat) {
      var verts = [];
      for (var i = 0; i < flat.length; i += 2) verts.push(unit(flat[i + 1], flat[i]));
      return verts;
    });
  });

  var lifts = [];
  for (var s = 0; s <= D.ARC_STEPS; s++) lifts.push(1 + D.LIFT * Math.sin(Math.PI * s / D.ARC_STEPS));

  /* The site pool, fixed and geographic. Nothing is ever spawned at a random
   * coordinate: the build script chose every one of these and every deal that
   * uses them, so a point can never land off a member's territory. */
  var sites = D.SITES.map(function (p) { return unit(p[0], p[1]); });

  /* Each deal's arcs, sampled on the sphere once at load. Only the two or three
   * live deals are ever projected, so this is memory, not per frame work. */
  var deals = D.DEALS.map(function (d) {
    var h = sites[d[0]];
    return {
      holder: h,
      legs: d[1].map(function (j) {
        var pts = [];
        for (var i = 0; i <= D.ARC_STEPS; i++) pts.push(slerp(h, sites[j], i / D.ARC_STEPS));
        return pts;
      }),
      ends: d[1].map(function (j) { return sites[j]; })
    };
  });

  /* How much of each arc is drawn. During the draw-in the arc grows out from
   * the holder toward the provider, which reads as a connection being made; a
   * plain fade read as specks appearing. Same as the build script. */
  function dealFrac(u) {
    if (u < 0) return 0;
    return u < D.D_IN ? u / D.D_IN : 1;
  }

  /* One deal's opacity over its life: draw in, hold and breathe once, release.
   * Same expression as the build script, deliberately. */
  function dealAlpha(u) {
    if (u < 0 || u >= D.D_LIFE) return 0;
    if (u < D.D_IN) return u / D.D_IN;
    if (u < D.D_IN + D.D_HOLD) {
      return 1 - D.BREATHE * 0.5 * (1 - Math.cos(2 * Math.PI * (u - D.D_IN) / D.D_HOLD));
    }
    return 1 - (u - D.D_IN - D.D_HOLD) / D.D_OUT;
  }

  /* Which deals are on screen, and which slot each owns. N_DEALS is a multiple
   * of MAX_LIVE, so a slot never changes hands at the wrap. */
  var liveBuf = [];
  function liveDeals(t) {
    liveBuf.length = 0;
    var base = Math.floor(t / D.STAGGER), c, u, k;
    for (c = base - D.MAX_LIVE + 1; c <= base; c++) {
      u = t - c * D.STAGGER;
      if (u >= 0 && u < D.D_LIFE) {
        k = ((c % D.N_DEALS) + D.N_DEALS) % D.N_DEALS;
        liveBuf.push([((c % D.MAX_LIVE) + D.MAX_LIVE) % D.MAX_LIVE, deals[k],
                      dealAlpha(u), dealFrac(u)]);
      }
    }
    return liveBuf;
  }

  /* ---- the elements already in the markup ------------------------------ */

  /* One instance per plate on the page. A real page carries one; the preview
   * carries several, and a plate marked still is left exactly as the markup
   * shipped it. */
  var views = [];
  roots.forEach(function (root) {
    if (root.closest("[data-globe-still]")) return;
    var v = {
      root:  root,
      grat:   root.querySelectorAll("[data-globe-graticule] path"),
      land:   root.querySelectorAll("[data-globe-land] path"),
      route:  root.querySelectorAll("[data-globe-routes] path"),
      site:   root.querySelectorAll("[data-globe-sites] circle"),
      holder: root.querySelectorAll("[data-globe-holders] circle"),
      seen:   true
    };
    var pool = D.MAX_LIVE * D.MAX_LEGS;
    if (v.grat.length !== grat.length || v.land.length !== land.length ||
        v.route.length !== pool || v.site.length !== pool ||
        v.holder.length !== D.MAX_LIVE) return;
    views.push(v);
  });
  if (!views.length) return;

  var lastCost = 0;

  var HIDDEN = ["", "0.00"];

  /* The frame's orientation, kept for the public API below. Seeded with the
   * RESTING matrix rather than left null: draw() may never run (reduced
   * motion returns before start()), and an API that answers "I do not know
   * yet" would force every caller to carry a second code path for a case that
   * has a perfectly good answer. D.LAM0 is the longitude the static plate was
   * rendered at, so a caller asking before the first frame gets the plate it
   * is actually looking at. */
  var lastM = matrix(D.LAM0);
  var frameSubs = [];

  function draw(t) {
    var t0 = performance.now();
    /* One clock. The view longitude and the deal cycle both come from it, and
     * the deal sequence is exactly one turn long, so the whole figure loops as
     * one thing rather than as two things drifting apart. */
    var lam0 = D.LAM0 + 360 * (t - D.T0) / D.TURN;
    var m = matrix(lam0), i, k, drew = 0;
    lastM = m;
    var gd = [], ld = [], rd = [], sd = [], hd = [];
    for (i = 0; i < grat.length; i++) gd.push(openPath(grat[i], m, null));
    for (i = 0; i < land.length; i++) {
      var rings = land[i], dstr = "";
      for (k = 0; k < rings.length; k++) {
        var one = ringPath(rings[k], m);
        if (one) dstr += (dstr ? " " : "") + one;
      }
      ld.push(dstr);
    }
    /* Fixed pools. A slot with no deal this frame gets an empty path and zero
     * opacity; nothing is created or destroyed. */
    var pool = D.MAX_LIVE * D.MAX_LEGS;
    for (i = 0; i < pool; i++) { rd.push(HIDDEN); sd.push(null); }
    for (i = 0; i < D.MAX_LIVE; i++) hd.push(null);
    var live = liveDeals(((t % D.TURN) + D.TURN) % D.TURN);
    for (i = 0; i < live.length; i++) {
      var slot = live[i][0], deal = live[i][1], a = live[i][2], f = live[i][3];
      var keep = Math.max(2, Math.round(f * D.ARC_STEPS) + 1);
      var p = project1(deal.holder, m), o = p[2] / D.FADE;
      o = (o > 1 ? 1 : o < 0 ? 0 : o) * a;
      hd[slot] = [rnd(p[0]), rnd(p[1]), o.toFixed(2)];
      for (k = 0; k < deal.legs.length; k++) {
        var idx = slot * D.MAX_LEGS + k;
        rd[idx] = [openPath(keep > D.ARC_STEPS ? deal.legs[k] : deal.legs[k].slice(0, keep),
                            m, lifts), a.toFixed(2)];
        var q = project1(deal.ends[k], m), oq = q[2] / D.FADE;
        oq = (oq > 1 ? 1 : oq < 0 ? 0 : oq) * a;
        sd[idx] = [rnd(q[0]), rnd(q[1]), oq.toFixed(2)];
      }
    }
    views.forEach(function (v) {
      if (!v.seen) return;
      drew++;
      var i, e;
      for (i = 0; i < gd.length; i++) v.grat[i].setAttribute("d", gd[i]);
      for (i = 0; i < ld.length; i++) v.land[i].setAttribute("d", ld[i]);
      for (i = 0; i < rd.length; i++) {
        v.route[i].setAttribute("d", rd[i][0]);
        v.route[i].setAttribute("opacity", rd[i][1]);
      }
      for (i = 0; i < sd.length; i++) {
        e = v.site[i];
        if (sd[i]) { e.setAttribute("cx", sd[i][0]); e.setAttribute("cy", sd[i][1]); e.setAttribute("opacity", sd[i][2]); }
        else e.setAttribute("opacity", "0.00");
      }
      for (i = 0; i < hd.length; i++) {
        e = v.holder[i];
        if (hd[i]) { e.setAttribute("cx", hd[i][0]); e.setAttribute("cy", hd[i][1]); e.setAttribute("opacity", hd[i][2]); }
        else e.setAttribute("opacity", "0.00");
      }
    });
    lastCost = performance.now() - t0;
    /* Backwards, so a subscriber that throws can be spliced out mid loop. It
     * IS spliced out: this file's promise is that the globe survives anything,
     * and a caller's bug must not be able to stop the rotation. The failure is
     * still visible, because whatever that caller was drawing stops moving. */
    for (i = frameSubs.length - 1; i >= 0; i--) {
      try { frameSubs[i](); } catch (e) { frameSubs.splice(i, 1); }
    }
    return drew;
  }

  /* ---- the loop, on a budget ------------------------------------------ */

  var mq = window.matchMedia("(prefers-reduced-motion: reduce)");
  var raf = 0, prev = 0, acc = 0, clock = D.T0;
  var STEP = 1000 / FPS_CAP;

  function anySeen() {
    for (var i = 0; i < views.length; i++) if (views[i].seen) return true;
    return false;
  }
  function running() { return !mq.matches && !document.hidden && anySeen(); }

  function tick(now) {
    raf = 0;
    if (!running()) return;
    var dt = prev ? Math.min(now - prev, 200) : 0;
    prev = now; acc += dt;
    if (acc >= STEP) {
      clock += acc / 1000;
      if (clock > D.T0 + D.TURN) clock -= D.TURN;   /* one period, no drift */
      acc = 0;
      draw(clock);
    }
    raf = requestAnimationFrame(tick);
  }

  function start() {
    if (raf || !running()) return;
    prev = 0; acc = STEP;
    raf = requestAnimationFrame(tick);
  }

  function stop() { if (raf) { cancelAnimationFrame(raf); raf = 0; } }

  /* Reduced motion stops the rotation AND the deal cycle, and puts the plate
   * back to its resting moment, which is the frame the markup shipped: a
   * representative moment with deals on it, not a bare globe. */
  function rest() {
    stop();
    clock = D.T0;
    views.forEach(function (v) { v.seen = true; });
    draw(clock);
  }

  if ("IntersectionObserver" in window) {
    var io = new IntersectionObserver(function (es) {
      es.forEach(function (e) {
        for (var i = 0; i < views.length; i++) {
          if (views[i].root === e.target) views[i].seen = e.isIntersecting;
        }
      });
      running() ? start() : stop();
    }, { threshold: 0 });
    views.forEach(function (v) { io.observe(v.root); });
  }
  document.addEventListener("visibilitychange", function () {
    document.hidden ? stop() : start();
  });
  var onMq = function () { mq.matches ? rest() : start(); };
  if (mq.addEventListener) { mq.addEventListener("change", onMq); }
  else if (mq.addListener) { mq.addListener(onMq); }

  /* ---- the public surface --------------------------------------------- */

  /* Always on, unlike window.cxGlobe below, which is a debug dump behind a
   * flag and may change shape without notice. THIS is the contract.
   *
   * WHY IT EXISTS: other decoration on the page has to be able to land on the
   * globe, and there is only one way to land on a turning globe, which is to
   * ask it where a point is on THIS frame. The alternative was to let a second
   * file re-derive the projection from a copy of D, and two copies of the same
   * arithmetic drift the moment anyone edits one of them.
   *
   * THREE MEMBERS, AND THAT IS THE WHOLE THING. Do not grow it into a general
   * geometry library; anything bigger belongs behind CX_GLOBE_DEBUG.
   *   project(lat, lon) -> {x, y, z, near}
   *       x and y are PLATE coordinates, the 1600x700 viewBox this file writes
   *       into. Turning those into some other element's coordinates is the
   *       caller's job and getScreenCTM is the honest way to do it: hard coding
   *       the placement percentages a second time is how the two layers come
   *       apart. z is the component toward the reader and near is z > 0.
   *   onFrame(fn) -> fn is called after every frame this file draws. It is NOT
   *       a timer: no frame, no call. That is deliberate, and it is what hands
   *       a subscriber this file's off-screen, tab-hidden and reduced-motion
   *       pausing for free rather than making it reimplement all three.
   *   disc -> the globe in plate coordinates, so nobody types 1130/350/265 out
   *       a second time.
   *
   * THE FALLBACK GUARANTEE IS UNCHANGED. If this file fails to load or throws
   * on the way in, this object never appears, and a caller that checks for it
   * before touching anything leaves the static markup exactly as it shipped.
   * That is why this is not a stub that answers with a made up frame. */
  window.cxGlobeAPI = {
    disc: { cx: CX, cy: CY, r: R },
    project: function (lat, lon) {
      var p = project1(unit(lat, lon), lastM);
      return { x: p[0], y: p[1], z: p[2], near: p[2] > 0 };
    },
    onFrame: function (fn) { if (typeof fn === "function") frameSubs.push(fn); }
  };

  if (window.CX_GLOBE_DEBUG) {
    window.cxGlobe = {
      draw: draw, matrix: matrix, openPath: openPath, ringPath: ringPath,
      project1: project1, grat: grat, land: land,
      lifts: lifts, views: views, D: D, sites: sites, deals: deals,
      dealAlpha: dealAlpha, dealFrac: dealFrac, liveDeals: liveDeals,
      cost: function () { return lastCost; },
      clock: function () { return clock; },
      running: running, stop: stop, start: start, rest: rest,
      active: function () { return !!raf; }
    };
  }

  if (mq.matches) return;   /* the markup is already the resting moment */
  start();
})();
