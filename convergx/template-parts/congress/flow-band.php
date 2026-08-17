<?php
/**
 * Congress: The flow band
 *
 * HARDCODED, AND IT MUST STAY THAT WAY. Generated SVG plus a globe instance,
 * driven by flow.js and globe.js at runtime. flow.js registers the
 * cx-flow-limb clipPath id and resolves node positions with getScreenCTM, so
 * a second instance on one page collides on that id and the diagram breaks in
 * a way that looks like a rendering glitch rather than a duplication. A
 * flexible-content layout would make duplicating it one click, so it is not one.
 *
 * The right-hand side is the homepage globe, reparented. Same component, one
 * instance per page.
 *
 * @package convergx
 */

defined( 'ABSPATH' ) || exit;
?>
<section class="flow-band" aria-labelledby="flow-title">
    <div class="wrap">
      <div class="flow-intro">
        <!-- RETITLED 2026-08-13. It read "Who attends the Congress", which is
             now the heading of ConvergX's OWN "Who attends" section further
             down this page, restored verbatim on Chip's mandate that day. Two
             visible headings asking the same question, two screens apart, read
             as a build error to a client reading her own copy back.
             THIS BAND IS THE DIAGRAM, hers is the prose. "Who is in the room"
             is the name this section carried before 2026-07-31 (see the
             deletion note above #speakers), so nothing new was invented.
             #flow-title is load-bearing: the section's aria-labelledby points
             at it. The id does not change, only the words. -->
        <h2 id="flow-title">Who is in the room</h2>
        <div class="flow-intro-body">
          <p class="flow-intro-desc">ConvergX convenes leaders from aerospace, defence, security and space; agriculture and food systems; mining and critical minerals; construction and infrastructure; advanced manufacturing; energy; health and life sciences; financial services; supply chain, logistics and trade; and digital infrastructure and cybersecurity.</p>
          <!-- THE ADMISSION STANDARD, added 2026-08-04 on Chip's instruction:
             "add a bit more of the original text from their current Who
             attends section... we need a bit of the VP level / decision maker
             thing."
             VERBATIM, and it is the strongest published line ConvergX has
             (section brief 04, "The admission standard"). Not one word is
             changed and none may be cut: it is their own copy, cleared
             because they publish it, and it stops being cleared the moment it
             is paraphrased into a claim of ours.
             IT ALSO RESTORES SOMETHING THIS PAGE LOST. The standard used to
             lead #tickets; that section came off on 2026-07-31 when Register
             became a storefront, and with it went the only place this page
             said who is allowed in. A band headed "Who attends the Congress"
             is exactly where it belongs.
             The registered glyph ConvergX prints after its own name is NOT
             carried. No such symbol appears anywhere on this site.
             The second instance on the site, the first being the panel that
             opens /congress/register/. Two is deliberate: the storefront needs
             it before a price, and this page needs it under that heading. -->
          <!-- *** THIS REVERSES CHIP'S 2026-08-04 ADDITION. Read the note above
               before restoring it. ***
               The admission standard paragraph stood here and is now rendered
               VERBATIM in ConvergX's own "Who attends" section further down
               this page, restored 2026-08-13 on the client-verbatim mandate.
               THE 2026-08-04 REASON FOR PUTTING IT HERE IS SPENT, and it is
               written out above: the standard was added to this band because
               #tickets came off on 2026-07-31 and took "the only place this
               page said who is allowed in" with it. That is no longer true.
               What stood here was the same sentence, word for word, about two
               screens above the section that now carries it. A client reading
               her own copy back sees the repeat, not the emphasis.
               STILL TWO INSTANCES ON THE SITE, which is the count the note
               above budgets: the panel that opens /congress/register/, and
               this page. Only the position inside this page changed.
               To restore, paste the paragraph back; it is in git. -->

          <!-- [[REVIEW: ConvergX's own Who Attends page describes the format as "a 2.5 day Congress, Summit or Executive Roundtable". This page and /congress/register/ both say three days. The published agenda runs Tuesday 08:00 to Thursday 17:45, and Tuesday's two roundtables are invite only, so a standard pass may cover 2.5 of the three. Kim Van Vliet to settle which figure is right; nothing is changed here in the meantime]] -->
        </div>
      </div>

      <figure class="flow">
        <div class="flow-heads">
          <div class="flow-head">
            <p class="label">Find capability</p>
            <p>Companies bring ConvergX a problem, or an RFP their own supply base cannot answer.</p>
          </div>
          <div class="flow-head flow-head--right">
            <p class="label">Get discovered</p>
            <p>ConvergX goes out to companies it has already vetted, in whatever industry and wherever they are.</p>
          </div>
        </div>

        <div class="flow-stage">
        <!-- NOT .fig-globe. The lifted markup carried that class and it has to
               come off: .fig-globe and its :not(--crop):not(--bleed) responsive
               variants size the plate for the HOMEPAGE HERO, at higher
               specificity than anything scoped here, and they were overriding
               this component's width. Measured before the fix: the globe landed
               at r 171 where the fan paths expected 258, so every line missed
               it. The only thing that class was contributing is a `color` for
               the paths to inherit, and .flow-globe sets that itself. The inner
               .fig-globe-* classes are untouched and still do their own work. -->
          <div class="flow-globe" aria-hidden="true">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1600 700" width="1600" height="700"
                 preserveAspectRatio="xMidYMid meet" data-globe="" aria-hidden="true" focusable="false">

              <!-- Hero plate: a converging network on a turning globe. Decorative
                   geometry. No figure number, no caption, no aria-label, no labels of any
                   kind, no numbers. See the notes file beside this one.

                   WHAT YOU ARE LOOKING AT IS FRAME ZERO, RENDERED STATICALLY. Everything
                   below is a complete, correct orthographic projection at the plate's
                   resting longitude. With scripts disabled this is the whole figure and it
                   is finished. _system/globe.js takes these same elements over and rewrites
                   their d attributes to turn the globe; it adds no elements and removes
                   none. Both sides are generated from one build script, so the resting
                   frame and the live frame zero are the same arithmetic.

                   Land: Natural Earth 110m admin 0, public domain, simplified and embedded
                   in globe.js. Nothing is fetched at runtime, ever. -->

              <defs>
                <clipPath id="cx-flow-limb"><circle cx="1130" cy="350" r="265"/></clipPath>
              </defs>

              <g clip-path="url(#cx-flow-limb)">

                <!-- Graticule. Clipped to the near hemisphere: the globe is
                     solid, so you do not see through it. -->
                <g class="fig-globe-grat" data-globe-graticule="">
                  <path class="fig-dash" vector-effect="non-scaling-stroke" d="M 1141 85 L 1149 86 L 1158 89 L 1166 95 L 1175 102 L 1183 111 L 1191 121 L 1198 134 L 1205 148 L 1211 164"/>
                  <path class="fig-dash" vector-effect="non-scaling-stroke" d="M 1015 111 L 1029 106 L 1044 102 L 1060 100 L 1076 99 L 1093 101 L 1110 105 L 1127 110 L 1144 118 L 1161 127 L 1178 137 L 1195 150 L 1211 164"/>
                  <path class="fig-dash" vector-effect="non-scaling-stroke" d="M 884 251 L 886 247 L 894 231 L 903 217 L 915 203 L 928 190 L 943 178 L 959 168 L 976 159 L 995 152 L 1014 146 L 1035 141 L 1056 139 L 1078 137 L 1100 138 L 1122 140 L 1145 144 L 1167 149 L 1189 156 L 1211 164"/>
                  <path class="fig-dash" vector-effect="non-scaling-stroke" d="M 880 439 L 879 435 L 875 420 L 874 404 L 874 389 L 876 373 L 881 356 L 887 340 L 895 324 L 904 308 L 916 292 L 929 277 L 944 262 L 960 248 L 977 235 L 996 223 L 1015 211 L 1035 201 L 1057 192 L 1078 184 L 1100 177 L 1123 172 L 1145 168 L 1167 165 L 1189 164 L 1211 164"/>
                  <path class="fig-dash" vector-effect="non-scaling-stroke" d="M 949 544 L 948 542 L 942 535 L 939 527 L 936 517 L 935 506 L 936 494 L 938 481 L 942 467 L 947 451 L 953 436 L 961 419 L 970 402 L 980 384 L 991 367 L 1004 349 L 1017 331 L 1031 313 L 1046 296 L 1062 279 L 1078 262 L 1095 247 L 1111 231 L 1128 217 L 1145 204 L 1162 192 L 1179 181 L 1195 172 L 1211 164"/>
                  <path class="fig-dash" vector-effect="non-scaling-stroke" d="M 1027 594 L 1028 593 L 1029 591 L 1032 586 L 1035 580 L 1038 572 L 1043 562 L 1048 551 L 1053 538 L 1060 524 L 1066 508 L 1074 491 L 1081 473 L 1090 455 L 1098 435 L 1107 415 L 1115 394 L 1124 373 L 1133 351 L 1142 330 L 1151 309 L 1160 288 L 1168 268 L 1176 248 L 1184 229 L 1192 211 L 1198 194 L 1205 178 L 1211 164"/>
                  <path class="fig-dash" vector-effect="non-scaling-stroke" d="M 1119 615 L 1120 615 L 1130 614 L 1139 611 L 1148 606 L 1157 599 L 1165 591 L 1174 580 L 1182 568 L 1190 554 L 1197 538 L 1204 521 L 1210 503 L 1216 484 L 1221 463 L 1225 442 L 1229 420 L 1231 397 L 1233 374 L 1235 351 L 1235 328 L 1235 305 L 1234 283 L 1232 261 L 1229 239 L 1225 219 L 1221 199 L 1216 181 L 1211 164"/>
                  <path class="fig-dash" vector-effect="non-scaling-stroke" d="M 1245 589 L 1245 589 L 1259 581 L 1271 572 L 1283 560 L 1293 548 L 1302 534 L 1310 518 L 1316 501 L 1321 483 L 1325 464 L 1327 444 L 1327 424 L 1326 402 L 1324 381 L 1320 359 L 1314 337 L 1307 315 L 1299 294 L 1290 273 L 1279 252 L 1267 232 L 1255 214 L 1241 196 L 1226 179 L 1211 164"/>
                  <path class="fig-dash" vector-effect="non-scaling-stroke" d="M 1376 449 L 1380 436 L 1385 419 L 1387 401 L 1387 383 L 1386 364 L 1382 346 L 1376 327 L 1369 309 L 1360 291 L 1349 273 L 1336 256 L 1322 240 L 1306 225 L 1289 210 L 1271 197 L 1252 184 L 1232 173 L 1211 164"/>
                  <path class="fig-dash" vector-effect="non-scaling-stroke" d="M 1380 261 L 1376 251 L 1368 238 L 1359 225 L 1348 214 L 1336 203 L 1322 194 L 1306 185 L 1289 178 L 1271 173 L 1252 168 L 1232 165 L 1211 164"/>
                  <path class="fig-dash" vector-effect="non-scaling-stroke" d="M 1311 156 L 1306 152 L 1298 148 L 1289 146 L 1278 145 L 1266 146 L 1254 148 L 1240 152 L 1226 157 L 1211 164"/>
                  <path class="fig-dash" vector-effect="non-scaling-stroke" d="M 1233 106 L 1233 106 L 1233 107 L 1232 110 L 1230 114 L 1228 121 L 1224 129 L 1221 139 L 1216 151 L 1211 164"/>
                  <path class="fig-dash" vector-effect="non-scaling-stroke" d=""/>
                  <path class="fig-dash" vector-effect="non-scaling-stroke" d="M 877 429 L 879 435 L 885 449 L 892 463 L 901 477 L 912 491 L 923 504 L 936 517 L 950 530 L 966 541 L 982 552 L 999 563 L 1016 572 L 1035 580 L 1053 587 L 1072 593 L 1091 598 L 1110 602 L 1129 605 L 1148 606 L 1166 606 L 1183 605 L 1200 603 L 1216 599 L 1231 594 L 1245 589 L 1245 589"/>
                  <path class="fig-dash" vector-effect="non-scaling-stroke" d="M 887 245 L 886 247 L 881 261 L 878 276 L 878 292 L 879 308 L 882 324 L 887 340 L 893 357 L 902 373 L 912 389 L 924 405 L 938 421 L 953 436 L 969 450 L 987 463 L 1005 476 L 1025 488 L 1045 499 L 1066 508 L 1088 516 L 1110 524 L 1132 529 L 1154 534 L 1176 537 L 1197 538 L 1218 538 L 1238 537 L 1258 534 L 1276 530 L 1294 525 L 1310 518 L 1325 510 L 1338 501 L 1350 490 L 1360 479 L 1368 466 L 1373 455"/>
                  <path class="fig-dash" vector-effect="non-scaling-stroke" d="M 1015 111 L 1002 118 L 990 126 L 980 136 L 972 146 L 964 156 L 959 168 L 955 180 L 952 193 L 952 206 L 953 220 L 955 234 L 960 248 L 966 263 L 973 277 L 982 291 L 992 305 L 1004 318 L 1017 331 L 1031 343 L 1046 355 L 1062 366 L 1079 376 L 1097 386 L 1115 394 L 1134 401 L 1153 407 L 1172 412 L 1191 416 L 1210 419 L 1229 420 L 1247 420 L 1264 419 L 1281 417 L 1297 413 L 1312 408 L 1326 402 L 1339 395 L 1350 387 L 1360 378 L 1369 368 L 1376 357 L 1382 346 L 1386 334 L 1388 321 L 1389 307 L 1388 294 L 1385 280 L 1383 271"/>
                  <path class="fig-dash" vector-effect="non-scaling-stroke" d="M 1166 95 L 1156 95 L 1146 95 L 1136 97 L 1127 99 L 1118 101 L 1110 105 L 1103 109 L 1096 113 L 1090 119 L 1085 124 L 1081 131 L 1078 137 L 1075 144 L 1074 152 L 1074 160 L 1074 168 L 1076 176 L 1078 184 L 1082 192 L 1086 200 L 1091 208 L 1097 216 L 1104 224 L 1111 231 L 1120 239 L 1128 245 L 1138 252 L 1147 258 L 1158 263 L 1168 268 L 1179 272 L 1190 275 L 1201 278 L 1212 281 L 1223 282 L 1234 283 L 1244 283 L 1254 282 L 1264 281 L 1273 279 L 1282 276 L 1290 273 L 1297 269 L 1304 264 L 1310 259 L 1315 253 L 1319 247 L 1322 240 L 1324 233 L 1326 226 L 1326 218 L 1326 210 L 1324 202 L 1322 194 L 1318 185 L 1314 177 L 1309 169 L 1303 161 L 1296 153 L 1289 146 L 1280 139 L 1272 132 L 1262 126 L 1252 120 L 1242 114 L 1232 110 L 1221 105 L 1210 102 L 1199 99 L 1188 97 L 1177 95 L 1166 95"/>
                </g>

                <!-- Land. Outlined in the cage tone, filled with the page
                     ground, which is what makes the sphere read as solid: the
                     fill occludes the graticule behind it. -->
                <g data-globe-land="">
                  <path class="fig-globe-land" vector-effect="non-scaling-stroke" d="M 1320 515 L 1328 517 L 1321 531 L 1308 542 L 1298 539 L 1309 521 L 1314 518 L 1320 515 Z"/>
                  <path class="fig-globe-land" vector-effect="non-scaling-stroke" d="M 1194 427 L 1192 435 L 1179 432 L 1170 451 L 1152 449 L 1193 427 L 1194 427 Z"/>
                  <path class="fig-globe-land" vector-effect="non-scaling-stroke" d="M 1033 137 L 1045 128 L 1062 123 L 1068 124 L 1090 118 L 1098 113 L 1135 122 L 1128 133 L 1110 146 L 1114 154 L 1108 167 L 1117 168 L 1121 164 L 1130 165 L 1111 178 L 1124 177 L 1122 183 L 1115 186 L 1110 181 L 1085 176 L 1071 177 L 1064 184 L 1063 206 L 1050 219 L 1057 219 L 1064 212 L 1075 214 L 1082 205 L 1097 199 L 1102 214 L 1094 225 L 1106 224 L 1093 263 L 1079 262 L 1067 249 L 1046 250 L 1065 255 L 1056 266 L 1065 276 L 1044 270 L 1053 269 L 1047 263 L 1052 252 L 1039 254 L 1010 237 L 1024 228 L 1030 208 L 1031 142 L 1033 137 Z M 1140 179 L 1143 177 L 1145 179 L 1142 182 L 1140 179 L 1140 179 Z M 1143 170 L 1153 161 L 1151 174 L 1147 176 L 1144 171 L 1143 170 Z M 1091 266 L 1084 269 L 1089 277 L 1083 287 L 1072 272 L 1093 265 L 1091 266 Z M 1104 185 L 1101 193 L 1095 183 L 1105 184 L 1104 185 Z M 1139 181 L 1139 194 L 1135 199 L 1132 198 L 1132 210 L 1123 212 L 1123 204 L 1117 215 L 1113 209 L 1109 217 L 1106 194 L 1123 196 L 1129 186 L 1126 171 L 1132 169 L 1139 170 L 1141 172 L 1136 174 L 1143 175 L 1136 179 L 1139 181 Z M 1140 164 L 1140 168 L 1130 165 L 1139 163 L 1140 164 Z M 1143 142 L 1137 146 L 1128 138 L 1133 136 L 1140 139 L 1143 139 L 1143 142 Z M 1146 153 L 1143 154 L 1140 149 L 1145 145 L 1148 153 L 1146 153 Z M 1134 154 L 1118 159 L 1114 146 L 1121 142 L 1122 147 L 1124 142 L 1127 144 L 1132 143 L 1134 150 L 1128 152 L 1134 153 L 1134 154 Z M 1132 159 L 1138 162 L 1128 163 L 1126 162 L 1133 157 L 1132 159 Z M 1151 160 L 1143 161 L 1149 157 L 1150 160 L 1151 160 Z M 1169 161 L 1173 163 L 1165 168 L 1159 164 L 1167 161 L 1169 161 Z M 1175 163 L 1187 176 L 1168 173 L 1166 175 L 1154 174 L 1152 167 L 1158 167 L 1158 170 L 1161 167 L 1165 168 L 1169 166 L 1171 169 L 1174 164 L 1175 163 Z"/>
                  <path class="fig-globe-land" vector-effect="non-scaling-stroke" d="M 1033 137 L 1030 208 L 1024 228 L 1010 237 L 1053 256 L 1047 265 L 1037 261 L 1030 268 L 1020 260 L 1004 266 L 1010 260 L 999 272 L 975 269 L 957 290 L 967 265 L 954 236 L 940 238 L 958 198 L 965 170 L 975 158 L 1000 142 L 1031 134 L 1025 139 L 1029 138 L 1033 137 Z"/>
                  <path class="fig-globe-land" vector-effect="non-scaling-stroke" d="M 1352 221 L 1365 249 L 1370 255 L 1369 266 L 1373 288 L 1366 286 L 1357 292 L 1356 299 L 1363 312 L 1358 317 L 1351 310 L 1352 303 L 1349 297 L 1346 305 L 1338 302 L 1337 282 L 1348 268 L 1344 266 L 1340 257 L 1339 240 L 1345 238 L 1342 229 L 1351 232 L 1351 221 L 1352 221 Z"/>
                  <path class="fig-globe-land" vector-effect="non-scaling-stroke" d="M 1363 312 L 1356 299 L 1357 292 L 1366 286 L 1371 290 L 1371 277 L 1374 277 L 1375 293 L 1379 302 L 1363 302 L 1364 310 L 1363 312 Z"/>
                  <path class="fig-globe-land" vector-effect="non-scaling-stroke" d=""/>
                  <path class="fig-globe-land" vector-effect="non-scaling-stroke" d=""/>
                  <path class="fig-globe-land" vector-effect="non-scaling-stroke" d="M 922 514 L 911 493 L 918 481 L 928 491 L 942 513 L 939 515 L 948 523 L 953 523 L 954 527 L 946 524 L 943 524 L 942 529 L 948 538 L 932 525 L 937 531 L 935 529 A 265 265 0 0 1 922 514 Z"/>
                  <path class="fig-globe-land" vector-effect="non-scaling-stroke" d="M 915 505 L 910 461 L 911 460 L 916 482 L 911 493 L 922 514 A 265 265 0 0 1 915 505 Z"/>
                  <path class="fig-globe-land" vector-effect="non-scaling-stroke" d="M 1301 533 L 1298 542 L 1292 546 L 1285 555 L 1285 557 L 1266 561 L 1271 551 L 1254 557 L 1254 552 L 1238 554 L 1255 546 L 1281 514 L 1315 503 L 1316 509 L 1303 529 L 1301 533 Z"/>
                  <path class="fig-globe-land" vector-effect="non-scaling-stroke" d="M 1339 503 L 1344 489 L 1373 434 L 1363 464 L 1340 501 L 1339 503 Z"/>
                  <path class="fig-globe-land" vector-effect="non-scaling-stroke" d="M 1328 517 L 1320 515 L 1327 498 L 1332 491 L 1338 492 L 1347 484 L 1339 503 L 1330 515 L 1328 517 Z"/>
                  <path class="fig-globe-land" vector-effect="non-scaling-stroke" d="M 1302 497 L 1297 483 L 1312 442 L 1345 425 L 1349 439 L 1332 482 L 1331 471 L 1325 482 L 1306 489 L 1300 496 L 1302 497 Z"/>
                  <path class="fig-globe-land" vector-effect="non-scaling-stroke" d="M 1307 454 L 1306 470 L 1297 483 L 1299 488 L 1268 509 L 1268 483 L 1281 457 L 1279 447 L 1295 450 L 1307 454 Z"/>
                  <path class="fig-globe-land" vector-effect="non-scaling-stroke" d="M 955 340 L 960 334 L 965 348 L 955 340 L 955 340 Z"/>
                  <path class="fig-globe-land" vector-effect="non-scaling-stroke" d="M 1285 135 L 1293 143 L 1301 150 L 1311 157 L 1328 176 L 1335 191 L 1342 199 L 1345 207 L 1352 221 L 1351 232 L 1342 229 L 1345 238 L 1339 240 L 1340 257 L 1344 266 L 1348 268 L 1337 282 L 1335 292 L 1338 302 L 1345 305 L 1346 305 L 1346 316 L 1353 328 L 1336 331 L 1328 328 L 1326 306 L 1308 305 L 1307 298 L 1300 290 L 1294 290 L 1286 272 L 1286 260 L 1269 240 L 1270 232 L 1285 235 L 1287 242 L 1279 244 L 1291 252 L 1288 247 L 1292 237 L 1284 229 L 1287 228 L 1292 233 L 1290 219 L 1295 215 L 1290 210 L 1299 206 L 1288 201 L 1282 192 L 1287 195 L 1298 200 L 1306 206 L 1300 199 L 1287 194 L 1282 190 L 1287 192 L 1285 185 L 1278 183 L 1256 160 L 1256 153 L 1264 156 L 1251 142 L 1252 136 L 1240 130 L 1240 131 L 1238 131 L 1214 118 L 1211 114 L 1181 109 L 1175 102 L 1179 101 L 1172 98 L 1202 100 L 1205 99 L 1201 97 L 1214 99 L 1218 102 L 1202 102 L 1210 102 L 1217 106 L 1221 105 L 1249 117 L 1268 125 L 1254 117 L 1258 118 A 265 265 0 0 1 1285 135 Z M 1251 161 L 1253 160 L 1257 163 L 1251 161 L 1251 161 Z M 1274 205 L 1266 187 L 1273 200 L 1279 207 L 1286 210 L 1280 213 L 1274 205 L 1274 205 Z M 1253 115 L 1250 115 L 1250 115 L 1248 113 A 265 265 0 0 1 1253 115 Z M 1170 105 L 1160 104 L 1161 101 L 1174 103 L 1175 102 L 1181 109 L 1176 107 L 1170 105 Z M 1320 329 L 1327 327 L 1322 335 L 1321 329 L 1320 329 Z"/>
                  <path class="fig-globe-land" vector-effect="non-scaling-stroke" d="M 1232 200 L 1238 201 L 1240 210 L 1231 203 L 1231 200 L 1232 200 Z M 1270 233 L 1256 243 L 1254 264 L 1257 287 L 1246 289 L 1243 274 L 1254 244 L 1259 231 L 1267 232 L 1270 233 Z"/>
                  <path class="fig-globe-land" vector-effect="non-scaling-stroke" d="M 1193 181 L 1204 183 L 1207 187 L 1200 187 L 1212 193 L 1206 196 L 1208 197 L 1206 201 L 1206 208 L 1202 209 L 1203 218 L 1194 223 L 1197 231 L 1190 230 L 1196 233 L 1160 238 L 1138 252 L 1136 232 L 1143 218 L 1155 213 L 1150 211 L 1157 211 L 1155 204 L 1159 202 L 1163 195 L 1165 177 L 1174 180 L 1175 177 L 1183 177 L 1192 182 L 1193 181 Z"/>
                  <path class="fig-globe-land" vector-effect="non-scaling-stroke" d="M 1221 599 L 1203 605 L 1207 601 L 1208 600 L 1220 597 L 1229 592 L 1230 593 L 1269 574 L 1256 583 L 1262 580 L 1258 582 A 265 265 0 0 1 1221 599 Z"/>
                  <path class="fig-globe-land" vector-effect="non-scaling-stroke" d="M 965 170 L 958 198 L 949 211 L 950 219 L 941 239 L 929 246 L 920 269 L 936 283 L 927 289 L 920 280 L 919 287 L 912 287 L 912 264 L 918 233 L 929 221 L 958 183 L 961 177 L 955 181 L 929 210 L 961 173 L 965 170 Z"/>
                  <path class="fig-globe-land" vector-effect="non-scaling-stroke" d="M 943 524 L 954 536 L 956 540 L 944 531 L 942 525 L 943 524 Z"/>
                  <path class="fig-globe-land" vector-effect="non-scaling-stroke" d="M 956 540 L 954 536 L 943 524 L 954 525 L 948 511 L 942 505 L 943 489 L 924 455 L 926 450 L 912 440 L 913 434 L 906 425 L 911 413 L 919 419 L 927 400 L 938 416 L 946 418 L 945 408 L 959 416 L 958 434 L 980 449 L 988 446 L 985 461 L 990 471 L 1037 515 L 1033 526 L 1015 529 L 999 545 L 974 537 L 958 541 L 956 540 Z"/>
                  <path class="fig-globe-land" vector-effect="non-scaling-stroke" d="M 914 441 L 926 450 L 924 455 L 943 489 L 942 500 L 931 488 L 928 492 L 914 480 L 911 460 L 915 448 L 914 441 Z"/>
                  <path class="fig-globe-land" vector-effect="non-scaling-stroke" d="M 919 419 L 906 416 L 915 448 L 911 460 L 897 432 L 895 382 L 897 392 L 912 389 L 921 414 L 918 416 L 919 419 Z"/>
                  <path class="fig-globe-land" vector-effect="non-scaling-stroke" d="M 934 411 L 927 400 L 919 419 L 921 414 L 906 371 L 914 370 L 922 353 L 942 358 L 932 364 L 931 376 L 941 393 L 935 406 L 934 411 Z"/>
                  <path class="fig-globe-land" vector-effect="non-scaling-stroke" d="M 922 353 L 918 356 L 919 346 L 913 348 L 911 337 L 914 334 L 922 351 L 922 353 Z"/>
                  <path class="fig-globe-land" vector-effect="non-scaling-stroke" d="M 914 334 L 911 336 L 910 322 L 913 318 L 914 331 L 914 334 Z"/>
                  <path class="fig-globe-land" vector-effect="non-scaling-stroke" d="M 915 325 L 914 306 L 925 314 L 915 324 L 915 325 Z"/>
                  <path class="fig-globe-land" vector-effect="non-scaling-stroke" d="M 925 314 L 915 308 L 915 296 L 921 296 L 926 312 L 925 314 Z"/>
                  <path class="fig-globe-land" vector-effect="non-scaling-stroke" d="M 912 287 L 919 287 L 920 280 L 924 286 L 920 296 L 912 290 L 912 287 Z"/>
                  <path class="fig-globe-land" vector-effect="non-scaling-stroke" d="M 959 416 L 945 408 L 946 418 L 935 414 L 941 393 L 931 373 L 935 361 L 942 362 L 936 371 L 946 365 L 947 375 L 965 394 L 967 408 L 958 412 L 959 416 Z"/>
                  <path class="fig-globe-land" vector-effect="non-scaling-stroke" d="M 967 439 L 957 431 L 958 412 L 967 408 L 972 424 L 966 434 L 967 439 Z"/>
                  <path class="fig-globe-land" vector-effect="non-scaling-stroke" d="M 974 444 L 967 439 L 967 426 L 982 433 L 976 443 L 974 444 Z"/>
                  <path class="fig-globe-land" vector-effect="non-scaling-stroke" d="M 987 445 L 974 444 L 982 433 L 987 443 L 987 445 Z M 1252 331 L 1257 333 L 1252 344 L 1257 355 L 1254 360 L 1230 360 L 1231 347 L 1222 333 L 1242 323 L 1251 331 L 1252 331 Z"/>
                  <path class="fig-globe-land" vector-effect="non-scaling-stroke" d="M 911 389 L 899 392 L 896 386 L 903 370 L 911 386 L 911 389 Z"/>
                  <path class="fig-globe-land" vector-effect="non-scaling-stroke" d="M 948 291 L 955 325 L 947 315 L 948 294 L 941 287 L 948 291 L 948 291 Z"/>
                  <path class="fig-globe-land" vector-effect="non-scaling-stroke" d="M 1269 574 L 1262 577 L 1262 573 L 1280 563 L 1286 560 L 1275 571 L 1269 574 Z"/>
                  <path class="fig-globe-land" vector-effect="non-scaling-stroke" d="M 1264 577 L 1245 587 L 1227 594 L 1247 579 L 1262 573 L 1264 576 L 1264 577 Z"/>
                  <path class="fig-globe-land" vector-effect="non-scaling-stroke" d="M 1229 592 L 1220 597 L 1208 600 L 1216 583 L 1262 573 L 1247 579 L 1236 587 L 1229 592 Z"/>
                  <path class="fig-globe-land" vector-effect="non-scaling-stroke" d="M 1144 479 L 1141 473 L 1154 469 L 1164 488 L 1142 483 L 1156 482 L 1148 480 L 1144 479 Z"/>
                  <path class="fig-globe-land" vector-effect="non-scaling-stroke" d="M 1164 488 L 1167 476 L 1194 480 L 1200 440 L 1237 467 L 1231 481 L 1197 488 L 1186 500 L 1164 489 L 1164 488 Z"/>
                  <path class="fig-globe-land" vector-effect="non-scaling-stroke" d="M 1152 449 L 1170 451 L 1179 432 L 1192 435 L 1193 428 L 1206 441 L 1200 440 L 1194 480 L 1164 479 L 1148 469 L 1154 451 L 1152 449 Z"/>
                  <path class="fig-globe-land" vector-effect="non-scaling-stroke" d="M 1217 517 L 1214 501 L 1227 497 L 1219 511 L 1217 517 Z"/>
                  <path class="fig-globe-land" vector-effect="non-scaling-stroke" d="M 1279 447 L 1281 457 L 1269 490 L 1237 488 L 1227 497 L 1216 486 L 1231 481 L 1237 467 L 1269 446 L 1276 450 L 1279 447 Z"/>
                  <path class="fig-globe-land" vector-effect="non-scaling-stroke" d="M 1217 517 L 1228 493 L 1237 488 L 1267 486 L 1270 492 L 1239 521 L 1228 524 L 1221 517 L 1217 517 Z"/>
                  <path class="fig-globe-land" vector-effect="non-scaling-stroke" d="M 1271 488 L 1264 528 L 1240 530 L 1238 522 L 1254 512 L 1270 489 L 1271 488 Z"/>
                  <path class="fig-globe-land" vector-effect="non-scaling-stroke" d="M 1211 499 L 1210 518 L 1195 522 L 1199 499 L 1210 499 L 1211 499 Z"/>
                  <path class="fig-globe-land" vector-effect="non-scaling-stroke" d="M 1176 499 L 1198 504 L 1192 521 L 1170 520 L 1175 499 L 1176 499 Z"/>
                  <path class="fig-globe-land" vector-effect="non-scaling-stroke" d="M 1155 486 L 1174 490 L 1175 505 L 1167 509 L 1163 498 L 1152 500 L 1147 490 L 1155 487 L 1155 486 Z"/>
                  <path class="fig-globe-land" vector-effect="non-scaling-stroke" d="M 1142 483 L 1155 486 L 1147 490 L 1142 484 L 1142 483 Z"/>
                  <path class="fig-globe-land" vector-effect="non-scaling-stroke" d="M 1171 508 L 1170 520 L 1157 509 L 1165 504 L 1170 508 L 1171 508 Z"/>
                  <path class="fig-globe-land" vector-effect="non-scaling-stroke" d="M 1152 500 L 1163 498 L 1165 504 L 1157 509 L 1152 503 L 1152 500 Z"/>
                  <path class="fig-globe-land" vector-effect="non-scaling-stroke" d="M 1188 500 L 1197 488 L 1217 484 L 1220 497 L 1189 501 L 1188 500 Z"/>
                  <path class="fig-globe-land" vector-effect="non-scaling-stroke" d="M 1308 504 L 1281 514 L 1265 526 L 1262 519 L 1268 509 L 1299 488 L 1308 503 L 1308 504 Z"/>
                  <path class="fig-globe-land" vector-effect="non-scaling-stroke" d="M 1276 520 L 1255 546 L 1238 552 L 1240 545 L 1252 541 L 1254 528 L 1264 528 L 1273 520 L 1276 520 Z"/>
                  <path class="fig-globe-land" vector-effect="non-scaling-stroke" d="M 1247 529 L 1257 531 L 1254 539 L 1237 549 L 1232 540 L 1245 533 L 1247 529 Z"/>
                  <path class="fig-globe-land" vector-effect="non-scaling-stroke" d="M 1298 542 L 1303 542 L 1294 553 L 1267 571 L 1256 575 L 1261 565 L 1268 563 L 1272 558 L 1285 557 L 1290 548 L 1297 542 L 1298 542 Z"/>
                  <path class="fig-globe-land" vector-effect="non-scaling-stroke" d="M 1303 541 L 1300 550 L 1293 556 L 1293 553 L 1303 542 L 1303 541 Z"/>
                  <path class="fig-globe-land" vector-effect="non-scaling-stroke" d="M 1303 544 L 1321 531 L 1312 541 L 1285 564 L 1276 571 L 1260 581 L 1269 574 L 1287 560 L 1284 560 L 1294 553 L 1293 557 L 1301 547 L 1303 544 Z"/>
                  <path class="fig-globe-land" vector-effect="non-scaling-stroke" d="M 1238 555 L 1254 552 L 1254 557 L 1271 551 L 1266 561 L 1272 559 L 1268 563 L 1261 565 L 1256 575 L 1216 583 L 1235 568 L 1237 555 L 1238 555 Z"/>
                  <path class="fig-globe-land" vector-effect="non-scaling-stroke" d="M 1316 539 L 1317 538 L 1327 527 A 265 265 0 0 1 1316 539 Z"/>
                  <path class="fig-globe-land" vector-effect="non-scaling-stroke" d="M 1263 418 L 1257 405 L 1260 388 L 1269 387 L 1264 417 L 1263 418 Z"/>
                  <path class="fig-globe-land" vector-effect="non-scaling-stroke" d="M 1193 428 L 1195 422 L 1225 409 L 1224 396 L 1260 388 L 1257 401 L 1264 423 L 1260 436 L 1269 446 L 1233 467 L 1206 441 L 1193 428 Z"/>
                  <path class="fig-globe-land" vector-effect="non-scaling-stroke" d="M 1339 384 L 1345 375 L 1347 379 L 1342 397 L 1339 398 L 1339 387 L 1339 384 Z"/>
                  <path class="fig-globe-land" vector-effect="non-scaling-stroke" d="M 1375 387 L 1381 370 L 1382 374 L 1381 386 L 1375 388 L 1375 387 Z"/>
                  <path class="fig-globe-land" vector-effect="non-scaling-stroke" d="M 1347 379 L 1348 359 L 1353 349 L 1367 371 L 1360 382 L 1350 379 L 1347 379 Z"/>
                  <path class="fig-globe-land" vector-effect="non-scaling-stroke" d="M 1381 385 L 1382 374 L 1386 375 L 1385 393 L 1378 412 L 1377 406 L 1381 395 L 1382 386 L 1381 385 Z"/>
                  <path class="fig-globe-land" vector-effect="non-scaling-stroke" d=""/>
                  <path class="fig-globe-land" vector-effect="non-scaling-stroke" d=""/>
                  <path class="fig-globe-land" vector-effect="non-scaling-stroke" d=""/>
                  <path class="fig-globe-land" vector-effect="non-scaling-stroke" d=""/>
                  <path class="fig-globe-land" vector-effect="non-scaling-stroke" d=""/>
                  <path class="fig-globe-land" vector-effect="non-scaling-stroke" d=""/>
                  <path class="fig-globe-land" vector-effect="non-scaling-stroke" d=""/>
                  <path class="fig-globe-land" vector-effect="non-scaling-stroke" d="M 1351 220 L 1345 207 L 1342 199 L 1335 191 L 1328 176 L 1312 159 L 1318 163 L 1310 156 L 1346 197 L 1357 216 L 1353 221 L 1351 220 Z"/>
                  <path class="fig-globe-land" vector-effect="non-scaling-stroke" d="M 1395 353 L 1394 343 L 1394 351 L 1392 348 L 1393 337 L 1391 333 L 1386 299 L 1384 297 L 1382 282 L 1387 292 L 1387 290 L 1389 297 L 1388 290 A 265 265 0 0 1 1395 353 Z"/>
                  <path class="fig-globe-land" vector-effect="non-scaling-stroke" d=""/>
                  <path class="fig-globe-land" vector-effect="non-scaling-stroke" d="M 1388 290 L 1389 292 L 1389 297 L 1387 288 L 1386 281 A 265 265 0 0 1 1388 290 Z"/>
                  <path class="fig-globe-land" vector-effect="non-scaling-stroke" d="M 1382 282 L 1384 297 L 1386 299 L 1391 333 L 1393 337 L 1387 360 L 1387 349 L 1383 344 L 1387 331 L 1381 294 L 1382 285 L 1382 282 Z"/>
                  <path class="fig-globe-land" vector-effect="non-scaling-stroke" d="M 1378 304 L 1377 290 L 1380 293 L 1380 284 L 1381 294 L 1386 316 L 1386 338 L 1383 344 L 1378 331 L 1377 322 L 1377 304 L 1378 304 Z"/>
                  <path class="fig-globe-land" vector-effect="non-scaling-stroke" d="M 1379 302 L 1375 293 L 1373 282 L 1375 290 L 1376 280 L 1380 283 L 1380 293 L 1377 290 L 1379 301 L 1379 302 Z"/>
                  <path class="fig-globe-land" vector-effect="non-scaling-stroke" d="M 1371 277 L 1370 255 L 1376 280 L 1375 290 L 1374 277 L 1371 277 L 1371 277 Z"/>
                  <path class="fig-globe-land" vector-effect="non-scaling-stroke" d="M 1358 317 L 1364 310 L 1363 302 L 1377 302 L 1378 321 L 1370 322 L 1367 332 L 1361 324 L 1362 316 L 1360 319 L 1358 317 Z"/>
                  <path class="fig-globe-land" vector-effect="non-scaling-stroke" d="M 1367 371 L 1358 361 L 1349 342 L 1356 334 L 1366 337 L 1370 322 L 1375 319 L 1383 344 L 1387 349 L 1387 360 L 1382 368 L 1367 369 L 1367 371 Z"/>
                  <path class="fig-globe-land" vector-effect="non-scaling-stroke" d="M 1339 383 L 1338 364 L 1349 354 L 1349 367 L 1342 383 L 1339 383 Z"/>
                  <path class="fig-globe-land" vector-effect="non-scaling-stroke" d="M 1257 287 L 1254 268 L 1255 246 L 1258 240 L 1264 244 L 1269 251 L 1265 268 L 1266 274 L 1271 279 L 1270 298 L 1264 302 L 1260 293 L 1257 287 Z"/>
                  <path class="fig-globe-land" vector-effect="non-scaling-stroke" d="M 1294 290 L 1307 298 L 1307 309 L 1292 314 L 1289 304 L 1293 293 L 1294 290 Z"/>
                  <path class="fig-globe-land" vector-effect="non-scaling-stroke" d="M 1308 305 L 1326 306 L 1329 313 L 1324 328 L 1316 328 L 1311 337 L 1312 331 L 1307 325 L 1292 329 L 1292 314 L 1306 306 L 1308 305 Z"/>
                  <path class="fig-globe-land" vector-effect="non-scaling-stroke" d="M 1289 304 L 1293 326 L 1273 321 L 1270 307 L 1288 302 L 1289 304 Z"/>
                  <path class="fig-globe-land" vector-effect="non-scaling-stroke" d="M 1280 334 L 1276 342 L 1262 341 L 1280 332 L 1280 334 Z"/>
                  <path class="fig-globe-land" vector-effect="non-scaling-stroke" d="M 1292 329 L 1286 343 L 1279 339 L 1280 334 L 1292 330 L 1292 329 Z"/>
                  <path class="fig-globe-land" vector-effect="non-scaling-stroke" d="M 1303 326 L 1312 330 L 1310 337 L 1304 326 L 1303 326 Z"/>
                  <path class="fig-globe-land" vector-effect="non-scaling-stroke" d="M 1310 337 L 1313 336 L 1313 344 L 1302 348 L 1290 341 L 1303 326 L 1309 335 L 1310 337 Z"/>
                  <path class="fig-globe-land" vector-effect="non-scaling-stroke" d="M 1292 294 L 1289 304 L 1281 296 L 1289 293 L 1292 294 Z"/>
                  <path class="fig-globe-land" vector-effect="non-scaling-stroke" d="M 1290 285 L 1294 290 L 1292 294 L 1281 296 L 1281 287 L 1289 286 L 1290 285 Z"/>
                  <path class="fig-globe-land" vector-effect="non-scaling-stroke" d="M 1269 309 L 1273 321 L 1267 326 L 1270 338 L 1256 339 L 1257 333 L 1251 328 L 1252 312 L 1255 305 L 1267 308 L 1269 309 Z"/>
                  <path class="fig-globe-land" vector-effect="non-scaling-stroke" d="M 1298 347 L 1313 344 L 1313 352 L 1301 360 L 1297 349 L 1298 347 Z"/>
                  <path class="fig-globe-land" vector-effect="non-scaling-stroke" d="M 1301 360 L 1310 356 L 1301 365 L 1305 382 L 1295 371 L 1301 360 L 1301 360 Z"/>
                  <path class="fig-globe-land" vector-effect="non-scaling-stroke" d="M 1309 355 L 1316 354 L 1311 362 L 1310 356 L 1309 355 Z"/>
                  <path class="fig-globe-land" vector-effect="non-scaling-stroke" d="M 1296 364 L 1294 370 L 1291 359 L 1295 363 L 1296 364 Z"/>
                  <path class="fig-globe-land" vector-effect="non-scaling-stroke" d="M 1281 341 L 1289 345 L 1280 349 L 1288 358 L 1274 349 L 1279 343 L 1281 341 Z"/>
                  <path class="fig-globe-land" vector-effect="non-scaling-stroke" d="M 1262 339 L 1264 344 L 1256 348 L 1252 344 L 1259 338 L 1262 339 Z"/>
                  <path class="fig-globe-land" vector-effect="non-scaling-stroke" d="M 1251 325 L 1247 329 L 1242 323 L 1250 324 L 1251 325 Z"/>
                  <path class="fig-globe-land" vector-effect="non-scaling-stroke" d="M 1252 312 L 1251 325 L 1244 323 L 1250 312 L 1252 312 Z"/>
                  <path class="fig-globe-land" vector-effect="non-scaling-stroke" d="M 1205 363 L 1213 366 L 1205 387 L 1201 377 L 1205 365 L 1205 363 Z"/>
                  <path class="fig-globe-land" vector-effect="non-scaling-stroke" d="M 1206 385 L 1213 366 L 1204 358 L 1243 363 L 1225 389 L 1210 387 L 1206 385 Z"/>
                  <path class="fig-globe-land" vector-effect="non-scaling-stroke" d="M 1219 309 L 1217 316 L 1208 317 L 1210 308 L 1217 308 L 1219 309 Z"/>
                  <path class="fig-globe-land" vector-effect="non-scaling-stroke" d=""/>
                  <path class="fig-globe-land" vector-effect="non-scaling-stroke" d=""/>
                  <path class="fig-globe-land" vector-effect="non-scaling-stroke" d=""/>
                  <path class="fig-globe-land" vector-effect="non-scaling-stroke" d="M 1386 282 L 1387 290 L 1376 280 L 1371 256 L 1370 255 L 1365 249 L 1351 220 L 1358 222 L 1357 216 L 1338 185 L 1329 175 L 1311 156 L 1317 163 L 1307 154 L 1301 150 L 1291 141 L 1285 135 A 265 265 0 0 1 1386 282 Z"/>
                  <path class="fig-globe-land" vector-effect="non-scaling-stroke" d=""/>
                  <path class="fig-globe-land" vector-effect="non-scaling-stroke" d="M 1264 342 L 1273 342 L 1271 354 L 1290 369 L 1285 369 L 1283 381 L 1281 371 L 1265 356 L 1257 357 L 1254 347 L 1264 344 L 1264 342 Z M 1280 380 L 1281 387 L 1273 384 L 1277 381 L 1280 380 Z"/>
                  <path class="fig-globe-land" vector-effect="non-scaling-stroke" d="M 1227 312 L 1220 296 L 1227 289 L 1225 293 L 1230 293 L 1227 300 L 1239 316 L 1239 322 L 1219 326 L 1226 321 L 1221 318 L 1223 311 L 1227 312 Z"/>
                  <path class="fig-globe-land" vector-effect="non-scaling-stroke" d="M 1206 251 L 1207 257 L 1196 261 L 1187 250 L 1203 250 L 1206 251 Z"/>
                  <path class="fig-globe-land" vector-effect="non-scaling-stroke" d="M 1350 328 L 1358 327 L 1359 338 L 1356 334 L 1354 340 L 1348 333 L 1349 329 L 1350 328 Z"/>
                  <path class="fig-globe-land" vector-effect="non-scaling-stroke" d="M 1336 331 L 1350 328 L 1346 335 L 1337 332 L 1336 331 Z"/>
                  <path class="fig-globe-land" vector-effect="non-scaling-stroke" d=""/>
                  <path class="fig-globe-land" vector-effect="non-scaling-stroke" d=""/>
                  <path class="fig-globe-land" vector-effect="non-scaling-stroke" d="M 1268 237 L 1286 260 L 1286 272 L 1279 278 L 1271 267 L 1273 254 L 1258 240 L 1267 234 L 1268 237 Z"/>
                  <path class="fig-globe-land" vector-effect="non-scaling-stroke" d="M 1273 321 L 1284 326 L 1280 331 L 1268 329 L 1272 321 L 1273 321 Z"/>
                  <path class="fig-globe-land" vector-effect="non-scaling-stroke" d="M 1342 456 L 1349 439 L 1358 450 L 1345 455 L 1342 456 Z"/>
                  <path class="fig-globe-land" vector-effect="non-scaling-stroke" d=""/>
                  <path class="fig-globe-land" vector-effect="non-scaling-stroke" d="M 941 500 L 942 505 L 952 519 L 948 523 L 939 515 L 942 513 L 928 492 L 931 488 L 941 499 L 941 500 Z"/>
                  <path class="fig-globe-land" vector-effect="non-scaling-stroke" d="M 1377 406 L 1377 419 L 1362 446 L 1358 448 L 1360 431 L 1368 426 L 1372 414 L 1377 406 Z"/>
                  <path class="fig-globe-land" vector-effect="non-scaling-stroke" d="M 1339 398 L 1347 379 L 1368 377 L 1376 391 L 1381 385 L 1381 395 L 1359 437 L 1339 400 L 1339 398 Z"/>
                  <path class="fig-globe-land" vector-effect="non-scaling-stroke" d="M 1224 396 L 1225 409 L 1195 422 L 1162 449 L 1153 447 L 1170 429 L 1192 417 L 1199 402 L 1211 392 L 1222 396 L 1224 396 Z"/>
                  <path class="fig-globe-land" vector-effect="non-scaling-stroke" d="M 1345 425 L 1312 442 L 1312 401 L 1337 391 L 1338 406 L 1332 400 L 1345 425 L 1345 425 Z"/>
                  <path class="fig-globe-land" vector-effect="non-scaling-stroke" d="M 1312 442 L 1307 454 L 1283 445 L 1276 450 L 1260 436 L 1270 404 L 1294 412 L 1299 400 L 1311 400 L 1313 427 L 1312 442 Z"/>
                  <path class="fig-globe-land" vector-effect="non-scaling-stroke" d="M 1364 457 L 1347 484 L 1333 493 L 1328 486 L 1346 451 L 1353 449 L 1356 461 L 1363 459 L 1364 457 Z"/>
                  <path class="fig-globe-land" vector-effect="non-scaling-stroke" d="M 1320 515 L 1307 523 L 1318 503 L 1328 499 L 1322 511 L 1320 515 Z"/>
                  <path class="fig-globe-land" vector-effect="non-scaling-stroke" d="M 1289 358 L 1280 349 L 1289 347 L 1289 355 L 1289 358 Z"/>
                  <path class="fig-globe-land" vector-effect="non-scaling-stroke" d="M 1287 342 L 1298 346 L 1297 357 L 1290 353 L 1288 344 L 1287 342 Z"/>
                  <path class="fig-globe-land" vector-effect="non-scaling-stroke" d="M 1317 505 L 1300 496 L 1306 489 L 1325 482 L 1332 470 L 1328 486 L 1332 491 L 1318 503 L 1317 505 Z"/>
                </g>

                <!-- The deal cycle. Fixed element pools, never added to and
                     never removed from: a slot that holds no deal this frame
                     carries an empty d and zero opacity. A holder is a filled
                     accent dot, a provider an unfilled accent circle. The accent
                     lives here and nowhere else, and a dormant site is not drawn
                     at all, which is what keeps the plate from reading as
                     confetti. -->
                <g data-globe-routes="">
                  <path class="fig-dash fig-globe-link" vector-effect="non-scaling-stroke" d="M 1297 358 L 1289 352" opacity="0.00"/>
                  <path class="fig-dash fig-globe-link" vector-effect="non-scaling-stroke" d="M 1297 358 L 1289 354" opacity="0.00"/>
                  <path class="fig-dash fig-globe-link" vector-effect="non-scaling-stroke" d="M 1297 358 L 1296 356" opacity="0.00"/>
                  <path class="fig-dash fig-globe-link" vector-effect="non-scaling-stroke" d="M 1297 358 L 1298 355" opacity="0.00"/>
                  <path class="fig-dash fig-globe-link" vector-effect="non-scaling-stroke" d="M 1297 358 L 1296 358" opacity="0.00"/>
                  <path class="fig-dash fig-globe-link" vector-effect="non-scaling-stroke" d="M 1268 353 L 1261 340 L 1255 327 L 1247 314 L 1239 301 L 1230 288 L 1221 275 L 1211 262 L 1200 249 L 1189 237 L 1178 225 L 1166 213 L 1154 202 L 1143 192 L 1131 183 L 1119 174 L 1107 167 L 1096 160 L 1084 154 L 1074 149 L 1063 145 L 1053 142 L 1044 140 L 1035 139 L 1027 139" opacity="0.50"/>
                  <path class="fig-dash fig-globe-link" vector-effect="non-scaling-stroke" d="M 1268 353 L 1268 351 L 1268 349 L 1268 347 L 1268 346 L 1267 344 L 1267 342 L 1266 340 L 1265 338 L 1264 336 L 1263 334 L 1262 332 L 1260 331 L 1258 329 L 1256 327 L 1254 325 L 1251 324 L 1249 322 L 1246 320 L 1244 319 L 1241 317 L 1238 316 L 1235 315 L 1232 313 L 1229 312" opacity="0.50"/>
                  <path class="fig-dash fig-globe-link" vector-effect="non-scaling-stroke" d="M 1268 353 L 1270 348 L 1272 344 L 1274 340 L 1275 335 L 1277 331 L 1278 326 L 1280 321 L 1281 317 L 1282 312 L 1282 307 L 1282 303 L 1283 298 L 1282 294 L 1282 289 L 1281 285 L 1280 281 L 1279 277 L 1278 273 L 1277 270 L 1275 266 L 1273 263 L 1271 260 L 1269 257 L 1267 254" opacity="0.50"/>
                  <path class="fig-dash fig-globe-link" vector-effect="non-scaling-stroke" d="" opacity="0.00"/>
                  <path class="fig-dash fig-globe-link" vector-effect="non-scaling-stroke" d="" opacity="0.00"/>
                  <path class="fig-dash fig-globe-link" vector-effect="non-scaling-stroke" d="M 1190 257 L 1184 248 L 1178 240 L 1171 231 L 1164 223 L 1157 215 L 1150 207 L 1142 199 L 1135 191 L 1127 184 L 1119 177 L 1112 171 L 1104 165 L 1096 160 L 1089 155 L 1081 151 L 1074 148 L 1067 145 L 1061 142 L 1054 140 L 1048 139 L 1042 138 L 1037 138 L 1032 138 L 1027 139" opacity="0.94"/>
                  <path class="fig-dash fig-globe-link" vector-effect="non-scaling-stroke" d="M 1190 257 L 1182 251 L 1174 245 L 1165 239 L 1156 233 L 1147 227 L 1138 221 L 1128 216 L 1119 211 L 1109 206 L 1099 202 L 1090 198 L 1080 194 L 1071 191 L 1061 188 L 1052 186 L 1044 185 L 1035 183 L 1027 183 L 1020 182 L 1012 182 L 1006 183 L 999 184 L 993 185 L 988 186" opacity="0.94"/>
                  <path class="fig-dash fig-globe-link" vector-effect="non-scaling-stroke" d="M 1190 257 L 1195 256 L 1199 255 L 1204 255 L 1209 254 L 1214 254 L 1218 253 L 1223 253 L 1228 253 L 1232 253 L 1237 253 L 1241 254 L 1245 254 L 1249 255 L 1252 256 L 1256 257 L 1259 258 L 1262 259 L 1265 261 L 1267 262 L 1270 264 L 1272 265 L 1274 267 L 1275 269 L 1277 270" opacity="0.94"/>
                  <path class="fig-dash fig-globe-link" vector-effect="non-scaling-stroke" d="M 1190 257 L 1192 260 L 1193 264 L 1195 267 L 1196 271 L 1198 275 L 1199 279 L 1201 284 L 1202 288 L 1203 293 L 1204 297 L 1205 302 L 1206 307 L 1206 312 L 1207 317 L 1207 323 L 1207 328 L 1208 333 L 1208 338 L 1207 343 L 1207 348 L 1207 353 L 1207 357 L 1206 362 L 1206 366" opacity="0.94"/>
                  <path class="fig-dash fig-globe-link" vector-effect="non-scaling-stroke" d="" opacity="0.00"/>
                  <path class="fig-dash fig-globe-link" vector-effect="non-scaling-stroke" d="M 1268 353 L 1262 340 L 1255 327 L 1248 314 L 1240 301 L 1232 288 L 1223 275 L 1213 262 L 1203 249 L 1193 236 L 1182 224 L 1171 213 L 1160 202 L 1148 192 L 1136 182 L 1125 173 L 1113 166 L 1102 159 L 1091 153 L 1081 148 L 1070 144 L 1061 141 L 1051 138 L 1043 137 L 1034 136" opacity="0.80"/>
                  <path class="fig-dash fig-globe-link" vector-effect="non-scaling-stroke" d="M 1268 353 L 1261 347 L 1253 342 L 1246 337 L 1237 331 L 1229 326 L 1220 320 L 1210 315 L 1200 309 L 1190 304 L 1179 298 L 1169 293 L 1158 288 L 1147 283 L 1136 279 L 1125 274 L 1114 270 L 1104 266 L 1093 263 L 1083 260 L 1073 257 L 1064 254 L 1055 252 L 1046 250 L 1037 248" opacity="0.80"/>
                  <path class="fig-dash fig-globe-link" vector-effect="non-scaling-stroke" d="" opacity="0.00"/>
                  <path class="fig-dash fig-globe-link" vector-effect="non-scaling-stroke" d="" opacity="0.00"/>
                  <path class="fig-dash fig-globe-link" vector-effect="non-scaling-stroke" d="" opacity="0.00"/>
                  <path class="fig-dash fig-globe-link" vector-effect="non-scaling-stroke" d="M 1190 257 L 1188 247 L 1186 237 L 1184 228 L 1181 218 L 1179 208 L 1176 199 L 1173 189 L 1170 180 L 1167 172 L 1163 163 L 1160 156 L 1156 148 L 1153 142 L 1149 135 L 1146 130 L 1142 125 L 1138 120 L 1135 117 L 1131 113 L 1127 111 L 1124 109 L 1121 107 L 1117 106 L 1114 106" opacity="0.98"/>
                  <path class="fig-dash fig-globe-link" vector-effect="non-scaling-stroke" d="M 1190 257 L 1193 258 L 1197 260 L 1200 261 L 1203 263 L 1207 265 L 1210 267 L 1213 269 L 1216 271 L 1219 274 L 1222 276 L 1225 279 L 1228 282 L 1230 285 L 1232 288 L 1235 291 L 1237 294 L 1238 297 L 1240 300 L 1241 304 L 1243 307 L 1244 310 L 1245 313 L 1246 316 L 1247 320" opacity="0.98"/>
                  <path class="fig-dash fig-globe-link" vector-effect="non-scaling-stroke" d="" opacity="0.00"/>
                  <path class="fig-dash fig-globe-link" vector-effect="non-scaling-stroke" d="" opacity="0.00"/>
                  <path class="fig-dash fig-globe-link" vector-effect="non-scaling-stroke" d="" opacity="0.00"/>
                </g>

                <g data-globe-sites="">
                  <circle class="fig-globe-site" vector-effect="non-scaling-stroke" cx="1022" cy="240" r="6" opacity="0.00"/>
                  <circle class="fig-globe-site" vector-effect="non-scaling-stroke" cx="1030" cy="263" r="6" opacity="0.00"/>
                  <circle class="fig-globe-site" vector-effect="non-scaling-stroke" cx="1229" cy="312" r="6" opacity="0.00"/>
                  <circle class="fig-globe-site" vector-effect="non-scaling-stroke" cx="1285" cy="290" r="6" opacity="0.00"/>
                  <circle class="fig-globe-site" vector-effect="non-scaling-stroke" cx="1239" cy="358" r="6" opacity="0.00"/>
                  <circle class="fig-globe-site" vector-effect="non-scaling-stroke" cx="1027" cy="139" r="6" opacity="0.50"/>
                  <circle class="fig-globe-site" vector-effect="non-scaling-stroke" cx="1229" cy="312" r="6" opacity="0.50"/>
                  <circle class="fig-globe-site" vector-effect="non-scaling-stroke" cx="1267" cy="254" r="6" opacity="0.50"/>
                  <circle class="fig-globe-site" vector-effect="non-scaling-stroke" cx="1130" cy="350" r="6" opacity="0.00"/>
                  <circle class="fig-globe-site" vector-effect="non-scaling-stroke" cx="1130" cy="350" r="6" opacity="0.00"/>
                  <circle class="fig-globe-site" vector-effect="non-scaling-stroke" cx="1027" cy="139" r="6" opacity="0.94"/>
                  <circle class="fig-globe-site" vector-effect="non-scaling-stroke" cx="988" cy="186" r="6" opacity="0.94"/>
                  <circle class="fig-globe-site" vector-effect="non-scaling-stroke" cx="1277" cy="270" r="6" opacity="0.94"/>
                  <circle class="fig-globe-site" vector-effect="non-scaling-stroke" cx="1206" cy="366" r="6" opacity="0.94"/>
                  <circle class="fig-globe-site" vector-effect="non-scaling-stroke" cx="1130" cy="350" r="6" opacity="0.00"/>
                  <circle class="fig-globe-site" vector-effect="non-scaling-stroke" cx="1034" cy="136" r="6" opacity="0.80"/>
                  <circle class="fig-globe-site" vector-effect="non-scaling-stroke" cx="1037" cy="248" r="6" opacity="0.80"/>
                  <circle class="fig-globe-site" vector-effect="non-scaling-stroke" cx="1130" cy="350" r="6" opacity="0.00"/>
                  <circle class="fig-globe-site" vector-effect="non-scaling-stroke" cx="1130" cy="350" r="6" opacity="0.00"/>
                  <circle class="fig-globe-site" vector-effect="non-scaling-stroke" cx="1130" cy="350" r="6" opacity="0.00"/>
                  <circle class="fig-globe-site" vector-effect="non-scaling-stroke" cx="1114" cy="106" r="6" opacity="0.98"/>
                  <circle class="fig-globe-site" vector-effect="non-scaling-stroke" cx="1247" cy="320" r="6" opacity="0.98"/>
                  <circle class="fig-globe-site" vector-effect="non-scaling-stroke" cx="1130" cy="350" r="6" opacity="0.00"/>
                  <circle class="fig-globe-site" vector-effect="non-scaling-stroke" cx="1130" cy="350" r="6" opacity="0.00"/>
                  <circle class="fig-globe-site" vector-effect="non-scaling-stroke" cx="1130" cy="350" r="6" opacity="0.00"/>
                </g>

                <g data-globe-holders="">
                  <circle class="fig-dot fig-globe-node" cx="1297" cy="358" r="6" opacity="0.00"/>
                  <circle class="fig-dot fig-globe-node" cx="1268" cy="353" r="6" opacity="0.50"/>
                  <circle class="fig-dot fig-globe-node" cx="1190" cy="257" r="6" opacity="0.94"/>
                  <circle class="fig-dot fig-globe-node" cx="1268" cy="353" r="6" opacity="0.80"/>
                  <circle class="fig-dot fig-globe-node" cx="1190" cy="257" r="6" opacity="0.98"/>
                </g>
              </g>

              <!-- The limb, outside the clip: it is the edge, not the contents. -->
              <circle class="fig-solid" vector-effect="non-scaling-stroke" cx="1130" cy="350" r="265"/>
            </svg>
            </div>
        <svg class="flow-art" viewBox="0 0 1600 540" preserveAspectRatio="none" aria-hidden="true" focusable="false">
          <!-- FEED. A requirement leaving an industry for ConvergX. TEN
               RESTING LINES, ONE TRAVELLING PULSE. There is only ever one
               requirement in flight, because only one industry is up at a time,
               so flow.js re-points the single pulse at the live row rather than
               the markup shipping eight pulses that mostly sit at zero.
               THE ORIGINS BELOW ARE A SNAPSHOT, measured at 1280 and written
               down for the no-script view. flow.js measures each row's dot for
               real and overwrites all ten, because the end of a word moves
               with the label and the type scale. Do not treat these as solved
               numbers and do not try to keep them in sync by hand. -->
          <path class="flow-line flow-feed" pathLength="100" vector-effect="non-scaling-stroke" d="M 248 27 C 497 27 552 270 800 270"/>
          <path class="flow-line flow-feed is-live" pathLength="100" vector-effect="non-scaling-stroke" d="M 381 81 C 570 81 612 270 800 270"/>
          <path class="flow-line flow-feed" pathLength="100" vector-effect="non-scaling-stroke" d="M 275 135 C 511 135 564 270 800 270"/>
          <path class="flow-line flow-feed" pathLength="100" vector-effect="non-scaling-stroke" d="M 318 189 C 535 189 583 270 800 270"/>
          <path class="flow-line flow-feed" pathLength="100" vector-effect="non-scaling-stroke" d="M 368 243 C 563 243 606 270 800 270"/>
          <path class="flow-line flow-feed" pathLength="100" vector-effect="non-scaling-stroke" d="M 80 297 C 404 297 476 270 800 270"/>
          <path class="flow-line flow-feed" pathLength="100" vector-effect="non-scaling-stroke" d="M 206 351 C 473 351 533 270 800 270"/>
          <path class="flow-line flow-feed" pathLength="100" vector-effect="non-scaling-stroke" d="M 224 405 C 483 405 541 270 800 270"/>
          <path class="flow-line flow-feed" pathLength="100" vector-effect="non-scaling-stroke" d="M 256 459 C 501 459 555 270 800 270"/>
          <path class="flow-line flow-feed" pathLength="100" vector-effect="non-scaling-stroke" d="M 317 513 C 534 513 583 270 800 270"/>
          <path class="flow-pulse flow-feed-pulse" pathLength="100" vector-effect="non-scaling-stroke" d="M 381 81 C 570 81 612 270 800 270"/>
          <!-- FAN. ConvergX reaching out, landing on the globe. FIVE SLOTS, of
               which flow.js draws three to five each slot, the count picked
               fresh: Chip asked for "3-5 random parts on the globe in NATO
               allianced countries", varying per industry. Any slot it does not
               use this time is emptied for the slot's duration.
               THEY MOVE: flow.js rewrites the d of each triple and the cx/cy of
               its node every frame, so the far end follows the site it is
               attached to as the globe turns, and goes out when that site goes
               round the back. See that file for which sites and why.
               ALL FIVE SHIP WITH REAL GEOMETRY, not three real and two empty.
               With scripts off five connections render and the figure is
               finished; empty elements in the markup would be a placeholder
               waiting for a script, which is the thing this component does not
               do. The five sit at 0.72r around the globe's centre at stage
               (1400, 270), spread so no two crowd and all five clear the mask.
               THE FIVE ARE INDEX ALIGNED across line, outbound pulse, return
               pulse and node. flow.js walks them by class in document order and
               refuses to run if any of the four counts is not five. -->
          <path class="flow-line flow-fan" pathLength="100" vector-effect="non-scaling-stroke" d="M 800 270 C 945 270 1111 254 1214 254"/>
          <path class="flow-pulse flow-fan-pulse" pathLength="100" vector-effect="non-scaling-stroke" style="--d:0.0s" d="M 800 270 C 945 270 1111 254 1214 254"/>
          <path class="flow-line flow-fan" pathLength="100" vector-effect="non-scaling-stroke" d="M 800 270 C 956 270 1135 377 1247 377"/>
          <path class="flow-pulse flow-fan-pulse" pathLength="100" vector-effect="non-scaling-stroke" style="--d:0.08s" d="M 800 270 C 956 270 1135 377 1247 377"/>
          <path class="flow-line flow-fan" pathLength="100" vector-effect="non-scaling-stroke" d="M 800 270 C 964 270 1151 138 1268 138"/>
          <path class="flow-pulse flow-fan-pulse" pathLength="100" vector-effect="non-scaling-stroke" style="--d:0.16s" d="M 800 270 C 964 270 1151 138 1268 138"/>
          <path class="flow-line flow-fan" pathLength="100" vector-effect="non-scaling-stroke" d="M 800 270 C 982 270 1191 440 1321 440"/>
          <path class="flow-pulse flow-fan-pulse" pathLength="100" vector-effect="non-scaling-stroke" style="--d:0.24s" d="M 800 270 C 982 270 1191 440 1321 440"/>
          <path class="flow-line flow-fan" pathLength="100" vector-effect="non-scaling-stroke" d="M 800 270 C 988 270 1202 94 1336 94"/>
          <path class="flow-pulse flow-fan-pulse" pathLength="100" vector-effect="non-scaling-stroke" style="--d:0.32s" d="M 800 270 C 988 270 1202 94 1336 94"/>
          <path class="flow-pulse flow-return-pulse" pathLength="100" vector-effect="non-scaling-stroke" style="--d:0.0s" d="M 800 270 C 945 270 1111 254 1214 254"/>
          <path class="flow-pulse flow-return-pulse" pathLength="100" vector-effect="non-scaling-stroke" style="--d:0.08s" d="M 800 270 C 956 270 1135 377 1247 377"/>
          <path class="flow-pulse flow-return-pulse" pathLength="100" vector-effect="non-scaling-stroke" style="--d:0.16s" d="M 800 270 C 964 270 1151 138 1268 138"/>
          <path class="flow-pulse flow-return-pulse" pathLength="100" vector-effect="non-scaling-stroke" style="--d:0.24s" d="M 800 270 C 982 270 1191 440 1321 440"/>
          <path class="flow-pulse flow-return-pulse" pathLength="100" vector-effect="non-scaling-stroke" style="--d:0.32s" d="M 800 270 C 988 270 1202 94 1336 94"/>
          <!-- CONVERGE, then the introduction. ONE STRAIGHT DROP from the hub's
               bottom edge to the output node, on Chip's instruction 2026-07-31:
               "Instead of the U shape here, just do a straight line to the
               result that ConvergX is spitting out." It was two curves swinging
               out to x 470 and x 730 and meeting underneath, which read as two
               more inputs arriving rather than as one result leaving. Do not
               reintroduce the second strand. -->
          <path class="flow-line flow-conv" pathLength="100" vector-effect="non-scaling-stroke" d="M 800 270 L 800 454"/>
          <path class="flow-pulse flow-conv-pulse" pathLength="100" vector-effect="non-scaling-stroke" style="--d:0.0s" d="M 800 270 L 800 454"/>
          <circle class="flow-node" style="--d:0.0s" cx="1214" cy="254" r="9" vector-effect="non-scaling-stroke"/>
          <circle class="flow-node" style="--d:0.08s" cx="1247" cy="377" r="9" vector-effect="non-scaling-stroke"/>
          <circle class="flow-node" style="--d:0.16s" cx="1268" cy="138" r="9" vector-effect="non-scaling-stroke"/>
          <circle class="flow-node" style="--d:0.24s" cx="1321" cy="440" r="9" vector-effect="non-scaling-stroke"/>
          <circle class="flow-node" style="--d:0.32s" cx="1336" cy="94" r="9" vector-effect="non-scaling-stroke"/>
          <circle class="flow-out" cx="800" cy="454" r="15" vector-effect="non-scaling-stroke"/>
        </svg>

          <!-- TEN VERTICALS, 2026-08-13, replacing the eight. The client approved
               this list in the Lindsay session and it is the only vertical list
               the launch site now carries. Their capitals and their ampersands
               are theirs and stay; the sentence-case sweep does not reach a
               proper-noun sector name.
               THREE THINGS MOVE TOGETHER OR NONE DO: this list, the ten
               .flow-feed paths in the art above, and ROWS plus ORDER in flow.js.
               styles.css repeat(10, 1fr) is the fourth. flow.js hard-returns if
               the counts disagree, so a partial edit shows as a dead band.
               THE LIST STAYS ALPHABETICAL, which is how a reader scans it. The
               ORDER THINGS LIGHT UP IN is a separate thing and it lives in
               flow.js. It is fixed, not reshuffled per loop; Chip wants a
               legible rotation, not noise. Aerospace ships lit and is ORDER[0].
               --d IS GONE FROM THESE ROWS. It scattered eight simultaneous
               animations; there is now one industry at a time and a class says
               which. Putting a delay back here would light a second one.
               --g is the gap between a word and its dot, varied per row so the
               ten feed origins do not line up on a neat diagonal. The dot is
               where that row's line starts, and flow.js measures it. -->
          <ul class="flow-sectors">
          <li class="flow-sector" style="--g:0.75rem"><span class="flow-sector-name">Advanced Manufacturing</span><span class="flow-dot" aria-hidden="true"></span></li>
          <li class="flow-sector is-live" style="--g:1.5rem"><span class="flow-sector-name">Aerospace, Defence, Security &amp; Space</span><span class="flow-dot" aria-hidden="true"></span></li>
          <li class="flow-sector" style="--g:1rem"><span class="flow-sector-name">Agriculture &amp; Food Systems</span><span class="flow-dot" aria-hidden="true"></span></li>
          <li class="flow-sector" style="--g:2rem"><span class="flow-sector-name">Construction &amp; Infrastructure</span><span class="flow-dot" aria-hidden="true"></span></li>
          <li class="flow-sector" style="--g:1.25rem"><span class="flow-sector-name">Digital Infrastructure &amp; Cybersecurity</span><span class="flow-dot" aria-hidden="true"></span></li>
          <li class="flow-sector" style="--g:0.5rem"><span class="flow-sector-name">Energy</span><span class="flow-dot" aria-hidden="true"></span></li>
          <li class="flow-sector" style="--g:1.75rem"><span class="flow-sector-name">Financial Services</span><span class="flow-dot" aria-hidden="true"></span></li>
          <li class="flow-sector" style="--g:0.875rem"><span class="flow-sector-name">Health &amp; Life Sciences</span><span class="flow-dot" aria-hidden="true"></span></li>
          <li class="flow-sector" style="--g:1.125rem"><span class="flow-sector-name">Mining &amp; Critical Minerals</span><span class="flow-dot" aria-hidden="true"></span></li>
          <li class="flow-sector" style="--g:1.375rem"><span class="flow-sector-name">Supply Chain, Logistics &amp; Trade</span><span class="flow-dot" aria-hidden="true"></span></li>
          </ul>

          <!-- THE MARK, NOT THE WORD, and the word is the fallback. Chip asked
               for the real logo here. shell.js already inlines the lockup once
               per page as <defs id="cx-logo"> and already has logoSvg(); this
               slot reuses both rather than shipping a second copy of 36 paths
               into this file. With scripts off the reader gets "ConvergX" set
               in the display face, which is correct rather than degraded.
               NOT an <img> of assets/brand/convergx-logo-black-tag.svg: that
               asset is black (invisible on this ground) and carries the
               RETIRED tagline, which is on the ledger and must not render. -->
          <!-- SHIPS LIT, paired with the first sector, which also ships lit.
               That is the no-script view and it is the point of the component
               rendered as one held moment: an industry and ConvergX up together
               with the connections drawn. flow.js takes .is-live over from here
               and moves it down the rotation; under reduced motion it leaves it
               exactly where the markup put it. -->
          <p class="flow-hub is-live"><span data-logo-slot>ConvergX</span></p>

          <!-- THE STATUS TRACK. Four lines, one box, one clock. Each fades in
               over the beat it names and out again, so at any moment the
               diagram is captioned by what it is doing. They are stacked in a
               single grid cell rather than positioned, which is what keeps the
               box the height of the tallest line and stops the layout jumping
               as they swap. -->
          <p class="flow-status" aria-hidden="true">
            <span style="--s:1">A requirement is submitted</span>
            <!-- NOT "its vetted network". The word invites a size comparison
                 ConvergX loses, which is the same reason /about/network/ is
                 titled "Who we convene". What ConvergX has is a set of
                 companies it has vetted, and that is what this says. -->
            <span style="--s:2">ConvergX asks the companies it has vetted</span>
            <span style="--s:3">Vetted companies answer</span>
            <span style="--s:4">One introduction is brokered</span>
          </p>
        </div>
      </figure>
    </div>
  </section>
