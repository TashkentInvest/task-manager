<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="Generator" content="Microsoft Word 15 (filtered)">

    <style>
        /* Font Definitions */
        @font-face {
            font-family: "Cambria Math";
            panose-1: 2 4 5 3 5 4 6 3 2 4;
        }

        @font-face {
            font-family: Calibri;
            panose-1: 2 15 5 2 2 2 4 3 2 4;
        }

        /* Style Definitions */
        p.MsoNormal,
        li.MsoNormal,
        div.MsoNormal {
            margin-top: 0cm;
            margin-right: 0cm;
            margin-bottom: 10.0pt;
            margin-left: 0cm;
            line-height: 115%;
            font-size: 11.0pt;
            font-family: "Calibri", sans-serif;
        }

        .MsoChpDefault {
            font-family: "Calibri", sans-serif;
        }

        .MsoPapDefault {
            margin-bottom: 8.0pt;
            line-height: 107%;
        }

        @page WordSection1 {
            size: 595.3pt 841.9pt;
            margin: 2.0cm 42.5pt 2.0cm 3.0cm;
        }

        div.WordSection1 {
            page: WordSection1;
        }
    </style>

</head>

<body lang="RU">

    <div class="WordSection1">

        <table class="MsoTableGrid" border="1" cellspacing="0" cellpadding="0" width="100%" style="width:100%; none">
            <tr style="height:482.1pt">
                <td width="737" valign="top" style="width:100%; padding:20px">
                    <div align="center">
                        <table class="MsoNormalTable" border="0" cellspacing="0" cellpadding="0" align="center"
                            style="border-collapse:collapse">
                            <tr style="height:12.9pt">
                                <td style="width: 100%; text-align: center; vertical-align: middle;">
                                    <p class="MsoNormal" align="center">
                                        <img style="width: 200px" src="https://toshkentinvest.uz/assets/frontend/tild6238-3031-4265-a564-343037346231/tic_logo_blue.png" alt=""> <br><br>
                                        <b><span lang="EN-US"
                                                style="line-height:115%; font-family: 'Times New Roman',serif;">“TOSHKENT
                                                INVEST KOMPANIYASI”</span></b>
                                    </p>
                                    <p class="MsoNormal" align="center"
                                        style="margin-top:6.0pt; margin-right:0cm; margin-bottom:0cm; margin-left:0cm; text-align:center;">
                                        <b><span lang="EN-US"
                                                style="line-height:115%; font-family: 'Times New Roman',serif;">AKSIYADORLIK
                                                JAMIYATI</span></b>
                                    </p>
                                </td>
                            </tr>
                            <tr style="height:12.9pt">
                                <td width="348"
                                    style="width:261.25pt; border:none; border-bottom:double windowtext 6.0pt; padding:0cm 0cm 0cm 1.5pt; height:12.9pt; text-align:center; vertical-align:middle;">
                                    <p class="MsoNormal" align="center"
                                        style="margin-top:6.0pt; margin-right:0cm; margin-bottom:0cm; margin-left:0cm; text-align:center;">
                                        <b><span lang="EN-US"
                                                style="line-height:115%; font-family: 'Times New Roman',serif;">BOSHQARUV
                                                RAISI VAZIFASINI BAJARUVCHI</span></b>
                                    </p>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <p class="MsoNormal" align="center" style="text-align:center"><b><span lang="EN-US"
                                style="font-size:12.0pt;line-height:115%;font-family: 'DejaVu Sans', sans-serif;">&nbsp;</span></b>
                    </p>
                    <p class="MsoNormal">
                        <b>Ma’sullar:</b>
                        @if ($task->task_users->isNotEmpty())
                        <ul>
                            @foreach ($task->task_users as $i)
                            <li><b>{{ $i->name ?? '' }}</b> — {{ $i->about ?? '' }}</li>
                            @endforeach
                        </ul>
                        @endif
                    </p>
                    <p class="MsoNormal" align="center" style="text-align:center"><span lang="EN-US"
                            style="font-size:10.0pt;line-height:115%;font-family: 'DejaVu Sans', sans-serif; color:black">&nbsp;</span>
                    </p>

          
                
                <p class="MsoNormal" style="text-align:justify;text-indent:15.65pt">
                    <b>
                        <span lang="EN-US" style="font-size:10.0pt;line-height:115%;font-family: 'DejaVu Sans', sans-serif; color:black;">
                            Muddati: {{$task->planned_completion_date->day}} 
                            {{ $monthNames[$task->planned_completion_date->month - 1] }} 
                            {{$task->planned_completion_date->year}}

                            {{-- {{$task->planned_completion_date->format('d.m.Y') ?? ''}} |  --}}
                        </span>
                    </b>
                </p>
                
                
                    </p>
                    <p class="MsoNormal" style="text-align:justify;text-indent:15.65pt"><span lang="EN-US"
                            style="font-size:10.0pt;line-height:115%;font-family: 'DejaVu Sans', sans-serif; color:black">&nbsp;</span>
                    </p>
                    <p class="MsoNormal" style="text-align:justify;text-indent:15.65pt;line-height:150%"><span
                            lang="EN-US"
                            style="font-size:12.0pt;line-height:150%;font-family: 'DejaVu Sans', sans-serif;color:black">&nbsp;</span>
                    </p>
                    <p class="MsoNormal" style="text-align:justify;text-indent:15.65pt;line-height:150%"><span
                            lang="EN-US"
                            style="font-size:12.0pt;line-height:150%;font-family: 'DejaVu Sans', sans-serif;color:black;">{{ $task->note }}
                        </span><span lang="UZ-CYR"
                            style="font-size:12.0pt; line-height:150%; font-family: 'DejaVu Sans', sans-serif;color:black;background:yellow">
                    </p>
                    <p class="MsoNormal" style="text-align:justify;text-indent:15.65pt"><span lang="EN-US"
                            style="font-size:12.0pt;line-height:115%;font-family: 'DejaVu Sans', sans-serif;">&nbsp;</span>
                    </p>
                    <p class="MsoNormal" style="text-align:justify;text-indent:15.65pt"><span lang="UZ-CYR"
                            style="font-size:12.0pt;line-height:115%;font-family: 'DejaVu Sans', sans-serif;">&nbsp;</span>
                    </p>
                    <p class="MsoNormal" align="right"
                        style="margin-right:15.75pt;text-align:right; text-indent:15.65pt">
                        <b><span lang="EN-US"
                                style="font-size:12.0pt;line-height:115%;font-family:'Times New Roman',serif">B.&nbsp;Shakirov</span></b>
                    </p>
                    <p class="MsoNormal" style="margin-right:15.75pt;text-indent:15.65pt"><b><span lang="EN-US"
                                style="font-size:12.0pt;line-height:115%;font-family: 'DejaVu Sans', sans-serif;">&nbsp;</span></b>
                    </p>
                    <p class="MsoNormal" style="margin-right:15.75pt;text-indent:15.65pt"><b><span lang="EN-US"
                                style="font-size:12.0pt;line-height:115%;font-family: 'DejaVu Sans', sans-serif;">&nbsp;</span></b>
                    </p>
                    <p class="MsoNormal" style="margin-right:15.75pt"><i><span lang="EN-US"
                                style="font-size:12.0pt;line-height:115%;font-family: 'DejaVu Sans', sans-serif;background:yellow">2024</span></i><i><span
                                lang="EN-US"
                                style="font-size:12.0pt;line-height:115%;font-family:'Times New Roman',serif">-yil
                                “___”-<span style="background:yellow">oktabr</span></span></i></p>
                    <p class="MsoNormal"><i><span lang="EN-US"
                                style="font-size:12.0pt;line-height:115%;font-family:'Times New Roman',serif"><span
                                    style="background:yellow">19-son</span></span></i></p>
                </td>
            </tr>
        </table>

        <p class="MsoNormal"><span lang="EN-US">&nbsp;</span></p>

    </div>

</body>

</html>
