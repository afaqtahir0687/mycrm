<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $helpContent['title'] ?? 'Help' }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; padding: 40px; background: #f5f5f5; line-height: 1.8; }
        .help-container { background: white; padding: 50px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); max-width: 1000px; margin: 0 auto; }
        h1 { color: #1976d2; margin-bottom: 40px; border-bottom: 3px solid #1976d2; padding-bottom: 15px; font-size: 28px; }
        h2 { color: #1976d2; margin-top: 35px; margin-bottom: 20px; font-size: 22px; font-weight: 600; border-left: 4px solid #1976d2; padding-left: 15px; }
        h3 { color: #424242; margin-top: 25px; margin-bottom: 12px; font-size: 18px; font-weight: 500; }
        ul, ol { margin-left: 40px; margin-bottom: 25px; margin-top: 10px; }
        ul { list-style-type: disc; }
        ol { list-style-type: decimal; }
        li { margin-bottom: 12px; color: #555; }
        .section { margin-bottom: 35px; padding: 20px; background: #f9f9f9; border-radius: 6px; border-left: 4px solid #e0e0e0; }
        .section:first-of-type { border-left-color: #1976d2; }
        strong { color: #333; font-weight: 600; }
        p { margin-bottom: 15px; color: #555; }
        .aim { font-size: 16px; color: #424242; margin-bottom: 20px; padding: 15px; background: #e3f2fd; border-radius: 4px; border-left: 4px solid #1976d2; }
        .process-flow-item { padding: 8px 0; border-bottom: 1px dotted #ddd; }
        .process-flow-item:last-child { border-bottom: none; }
        @media print {
            body { padding: 20px; background: white; }
            .help-container { box-shadow: none; }
        }
    </style>
</head>
<body>
    <div class="help-container">
        <h1>{{ $helpContent['title'] ?? 'Help Documentation' }}</h1>
        
        @if(isset($helpContent['aims']))
        <div class="section">
            <h2>Aims and Objectives</h2>
            <div class="aim">
                <strong>Aim:</strong> {{ $helpContent['aims'] }}
            </div>
            
            @if(isset($helpContent['objectives']) && count($helpContent['objectives']) > 0)
            <h3>Objectives:</h3>
            <ul>
                @foreach($helpContent['objectives'] as $objective)
                <li>{{ $objective }}</li>
                @endforeach
            </ul>
            @endif
        </div>
        @endif
        
        @if(isset($helpContent['linked_forms']))
        <div class="section">
            <h2>Linked Forms/Data</h2>
            <ul>
                @foreach($helpContent['linked_forms'] as $link)
                <li>{{ $link }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        
        @if(isset($helpContent['functionality']))
        <div class="section">
            <h2>Functionality</h2>
            <ul>
                @foreach($helpContent['functionality'] as $feature)
                <li>{{ $feature }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        
        @if(isset($helpContent['data_entry']))
        <div class="section">
            <h2>Step-by-Step Data Entry</h2>
            <ol>
                @foreach($helpContent['data_entry'] as $step)
                <li>{{ $step }}</li>
                @endforeach
            </ol>
        </div>
        @endif
        
        @if(isset($helpContent['process_flow']))
        <div class="section">
            <h2>Process Flow</h2>
            <div style="background: white; padding: 20px; border-radius: 4px;">
                @foreach($helpContent['process_flow'] as $flow)
                <div class="process-flow-item">{{ $flow }}</div>
                @endforeach
            </div>
        </div>
        @endif
        
        @if(isset($helpContent['reports']))
        <div class="section">
            <h2>Related Reports</h2>
            <ul>
                @foreach($helpContent['reports'] as $report)
                <li>{{ $report }}</li>
                @endforeach
            </ul>
        </div>
        @endif
    </div>
</body>
</html>

