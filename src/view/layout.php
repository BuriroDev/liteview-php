<?php
$currentTable = $tableName ?? null;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>LiteView - SQLite Web Data Viewer</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --bg-color: #f1ead4;
            --toolbar-bg-top: #4a4b51;
            --toolbar-bg-bottom: #2b2b31;
            --panel-border: #888;
            --header-bg: #444;
            --table-header-bg: #03348d;
            --row-even: #ffffff;
            --row-odd: #cde2f5;
            --selection-bg: #4a4a4a;
        }

        body {
            margin: 0;
            padding: 0;
            background-color: var(--bg-color);
            font-family: 'Lucida Grande', 'Segoe UI', Tahoma, Arial, sans-serif;
            font-size: 12px;
            display: flex;
        }

        .mac-window {
            background-color: var(--bg-color);
            display: flex;
            flex-direction: column;
            width: 100vw;
            height: 100vh;
            overflow: hidden;
        }

        .title-bar {
            background: linear-gradient(to bottom, #f9f9f9, #d0d0d0);
            border-bottom: 1px solid #999;
            height: 22px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 10px;
        }

        .window-controls {
            display: flex;
            gap: 6px;
        }

        .window-controls div {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            border: 1px solid rgba(0, 0, 0, 0.2);
            box-shadow: inset 0 1px 2px rgba(255, 255, 255, 0.5);
        }

        .close-btn {
            background-color: #ff5f56;
        }

        .min-btn {
            background-color: #ffbd2e;
        }

        .max-btn {
            background-color: #27c93f;
        }

        .title-icons {
            display: flex;
            gap: 8px;
            color: #555;
        }

        .toolbar-area {
            display: flex;
            padding: 8px 10px 8px 10px;
            border-bottom: 1px solid #c0bca8;
            align-items: center;
        }

        .logo-container {
            width: 180px;
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-right: 10px;
        }

        .logo-container img {
            width: 160px;
            height: auto;
        }

        .logo-subtitle {
            font-size: 11px;
            text-align: center;
            margin-top: 2px;
            color: #333;
        }

        .toolbar {
            display: flex;
            background: linear-gradient(to bottom, var(--toolbar-bg-top), var(--toolbar-bg-bottom));
            border: 1px solid #111;
            border-radius: 4px;
            padding: 4px 6px;
            gap: 4px;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.2), 0 1px 2px rgba(0, 0, 0, 0.2);
        }

        .toolbar-btn {
            background: linear-gradient(to bottom, #606774, #414a56);
            border: 1px solid #222;
            border-radius: 3px;
            color: #eee;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            width: 60px;
            height: 50px;
            font-size: 10px;
            text-shadow: 0 -1px 0 #000;
            cursor: pointer;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.3);
        }

        .toolbar-btn:hover {
            background: linear-gradient(to bottom, #6a717f, #4a5462);
        }

        .toolbar-btn.active {
            background: linear-gradient(to bottom, #414a56, #505866);
            border-color: #000;
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.5);
        }

        .toolbar-btn i {
            font-size: 18px;
            margin-bottom: 4px;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.5);
        }

        .toolbar-btn i.fa-key {
            color: #5bc0de;
        }

        .toolbar-btn i.fa-table {
            color: #fff;
        }

        .toolbar-btn i.fa-sync {
            color: #5cb85c;
        }

        .toolbar-btn i.fa-save {
            color: #5bc0de;
        }

        .toolbar-btn i.fa-file-export {
            color: #5cb85c;
        }

        .toolbar-btn i.fa-share-square {
            color: #5cb85c;
        }

        .main-content {
            display: flex;
            flex: 1;
            border-top: 2px solid #fff;
            overflow: hidden;
        }

        .sidebar {
            width: 190px;
            border-right: 1px solid var(--panel-border);
            background-color: #fff;
            display: flex;
            flex-direction: column;
        }

        .panel-header {
            background-color: var(--header-bg);
            color: white;
            font-weight: bold;
            padding: 4px 8px;
            font-size: 11px;
            text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.8);
            border-bottom: 1px solid #222;
        }

        .sidebar-tree {
            flex: 1;
            overflow-y: auto;
            padding: 8px 4px;
            background: #fff;
        }

        .tree-node {
            display: flex;
            align-items: center;
            padding: 2px 4px;
            cursor: pointer;
            gap: 4px;
            white-space: nowrap;
        }

        .tree-node:hover {
            background-color: #e5e5e5;
        }

        .tree-node.selected {
            background-color: #606060;
            color: white;
        }

        .tree-node i {
            font-size: 13px;
            width: 14px;
            text-align: center;
        }

        .tree-node i.fa-database {
            color: #555;
        }

        .tree-node i.fa-folder {
            color: #4682b4;
        }

        .tree-node i.fa-table {
            color: #777;
            font-size: 12px;
        }

        .tree-node i.fa-eye {
            color: #777;
            font-size: 12px;
        }

        .tree-node.selected i {
            color: white;
        }

        .tree-indent {
            margin-left: 16px;
        }

        .tree-indent-2 {
            margin-left: 32px;
        }

        .content-area {
            flex: 1;
            display: flex;
            flex-direction: column;
            background: var(--bg-color);
            padding: 0 4px 4px 4px;
            min-width: 0;
        }

        .content-header {
            font-weight: bold;
            padding: 6px 4px;
            font-size: 12px;
            color: #000;
        }

        .table-wrapper {
            flex: 2;
            background: white;
            border: 1px solid var(--panel-border);
            overflow: auto;
            margin-bottom: 4px;
            box-shadow: inset 1px 1px 3px rgba(0, 0, 0, 0.1);
        }

        table.data-table {
            width: 100%;
            border-collapse: collapse;
            font-family: Arial, sans-serif;
        }

        table.data-table th,
        table.data-table td {
            border: 1px solid #b8b8b8;
            padding: 3px 6px;
            white-space: nowrap;
        }

        table.data-table th {
            background: linear-gradient(to bottom, #0548b8, #012873);
            color: white;
            text-align: left;
            font-weight: bold;
            font-size: 11px;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        table.data-table tr:nth-child(even) {
            background-color: var(--row-even);
        }

        table.data-table tr:nth-child(odd) {
            background-color: var(--row-odd);
        }

        table.data-table tr:hover {
            background-color: #ffffcc;
        }

        .query-editor-wrapper {
            margin-bottom: 4px;
        }

        .query-editor {
            width: 100%;
            height: 40px;
            border: 1px solid var(--panel-border);
            background: white;
            padding: 4px;
            font-family: 'Courier New', Courier, monospace;
            font-size: 12px;
            resize: none;
            box-sizing: border-box;
            box-shadow: inset 1px 1px 3px rgba(0, 0, 0, 0.1);
        }

        .query-results-wrapper {
            flex: 1;
            background: white;
            border: 1px solid var(--panel-border);
            overflow: auto;
            box-shadow: inset 1px 1px 3px rgba(0, 0, 0, 0.1);
        }

        .footer {
            text-align: center;
            padding: 4px;
            font-size: 10px;
            color: #555;
            border-top: 1px solid var(--panel-border);
            background: var(--bg-color);
        }

        a {
            text-decoration: none;
            color: inherit;
            display: block;
            width: 100%;
        }

        /* Custom scrollbars */
        ::-webkit-scrollbar {
            width: 12px;
            height: 12px;
        }

        ::-webkit-scrollbar-track {
            background: #e0e0e0;
            border-left: 1px solid #ccc;
        }

        ::-webkit-scrollbar-thumb {
            background: #b0b0b0;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #909090;
        }
    </style>
</head>

<body>

    <div class="mac-window">
        <div class="toolbar-area">
            <div class="logo-container">
                <?php $basePath = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\'); ?>
                <img src="<?php echo htmlspecialchars($basePath); ?>/assets/logo.png" alt="LiteView Logo">
            </div>

            <div class="toolbar">
                <div class="toolbar-btn">
                    <i class="fa-solid fa-key"></i>
                    Connect
                </div>
                <div class="toolbar-btn active">
                    <i class="fa-solid fa-table"></i>
                    SQL Query
                </div>
                <div class="toolbar-btn" id="refresh-btn">
                    <i class="fa-solid fa-sync"></i>
                    Refresh
                </div>
                <div class="toolbar-btn">
                    <i class="fa-solid fa-save"></i>
                    Save
                </div>
                <div class="toolbar-btn">
                    <i class="fa-solid fa-file-export"></i>
                    Export
                </div>
            </div>
        </div>

        <div class="main-content">
            <div class="sidebar">
                <div class="panel-header">Database Objects</div>
                <div class="sidebar-tree">
                    <div class="tree-parent" onclick="toggleTree(this)">
                        <div class="tree-node"><i class="toggle-icon fa-solid fa-minus-square" style="color:#888; font-size:10px;"></i> <i class="fa-solid fa-database"></i> Database</div>
                    </div>
                    <div class="tree-children">
                        <div class="tree-parent" onclick="toggleTree(this)">
                            <div class="tree-node tree-indent"><i class="toggle-icon fa-solid fa-minus-square" style="color:#888; font-size:10px;"></i> <i class="fa-solid fa-folder"></i> Tables</div>
                        </div>
                        <div class="tree-children">
                            <?php if (isset($tablesList)): ?>
                                <?php foreach ($tablesList as $table): ?>
                                    <a href="<?php echo htmlspecialchars($_SERVER['SCRIPT_NAME']); ?>/view/<?php echo urlencode($table['name']); ?>">
                                        <div class="tree-node tree-indent-2 <?php echo ($currentTable === $table['name']) ? 'selected' : ''; ?>">
                                            <i class="fa-solid fa-table"></i> <?php echo htmlspecialchars($table['name']); ?>
                                        </div>
                                    </a>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>

                        <div class="tree-parent" onclick="toggleTree(this)">
                            <div class="tree-node tree-indent"><i class="toggle-icon fa-solid fa-plus-square" style="color:#888; font-size:10px;"></i> <i class="fa-solid fa-folder"></i> Views</div>
                        </div>
                        <div class="tree-children" style="display: none;">
                            <div class="tree-node tree-indent-2"><i class="fa-solid fa-eye"></i> Views</div>
                            <div class="tree-node tree-indent-2"><i class="fa-solid fa-eye"></i> View rnoots</div>
                        </div>

                        <div class="tree-parent" onclick="toggleTree(this)">
                            <div class="tree-node tree-indent"><i class="toggle-icon fa-solid fa-plus-square" style="color:#888; font-size:10px;"></i> <i class="fa-solid fa-folder"></i> Database Objects</div>
                        </div>
                        <div class="tree-children" style="display: none;"></div>

                        <div class="tree-parent" onclick="toggleTree(this)">
                            <div class="tree-node tree-indent"><i class="toggle-icon fa-solid fa-plus-square" style="color:#888; font-size:10px;"></i> <i class="fa-solid fa-folder"></i> Storrels</div>
                        </div>
                        <div class="tree-children" style="display: none;"></div>
                    </div>
                </div>
            </div>

            <div class="content-area">
                <div class="content-header">SQLite Table</div>

                <div class="table-wrapper">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <?php if (isset($results) && !empty($results)): ?>
                                    <?php foreach (array_keys($results[0]) as $columnName): ?>
                                        <th><?php echo htmlspecialchars($columnName); ?></th>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <th>id</th>
                                    <th>part_name</th>
                                    <th>type</th>
                                    <th>name</th>
                                    <th>bannty</th>
                                    <th>created</th>
                                    <th>amount</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (isset($results) && !empty($results)): ?>
                                <?php foreach ($results as $row): ?>
                                    <tr>
                                        <?php foreach ($row as $data): ?>
                                            <td><?php echo htmlspecialchars($data); ?></td>
                                        <?php endforeach; ?>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td>1</td>
                                    <td>Beeir</td>
                                    <td>Anma</td>
                                    <td>New</td>
                                    <td>1</td>
                                    <td>1990.000</td>
                                    <td>3920.00</td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>Familly</td>
                                    <td>Amne</td>
                                    <td>Header</td>
                                    <td>2</td>
                                    <td>1990.000</td>
                                    <td>2960.00</td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>Moyner</td>
                                    <td>Franc</td>
                                    <td>Warnior</td>
                                    <td>3</td>
                                    <td>1990.000</td>
                                    <td>2970.00</td>
                                </tr>
                                <tr>
                                    <td>4</td>
                                    <td>Hankson</td>
                                    <td>Frank</td>
                                    <td>Gileorg</td>
                                    <td>4</td>
                                    <td>1990.379</td>
                                    <td>2360.00</td>
                                </tr>
                                <tr>
                                    <td>5</td>
                                    <td>Wilkley</td>
                                    <td>Mark</td>
                                    <td>Emith</td>
                                    <td>5</td>
                                    <td>2000.000</td>
                                    <td>3900.00</td>
                                </tr>
                                <tr>
                                    <td>6</td>
                                    <td>Prontnson</td>
                                    <td>Adam</td>
                                    <td>Smith</td>
                                    <td>6</td>
                                    <td>1990.375</td>
                                    <td>3960.00</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <div class="query-editor-wrapper">
                    <textarea class="query-editor">SELECT SELECT data SELECT * FROM 'Lite'
FROM View, FROM 'Lite'</textarea>
                </div>

                <div class="query-results-wrapper">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>id</th>
                                <th>name</th>
                                <th>type</th>
                                <th>value</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1002</td>
                                <td>Gartner De Baquinor</td>
                                <td>Female</td>
                                <td>Margane</td>
                            </tr>
                            <tr>
                                <td>1003</td>
                                <td>Uo Carbagos Prevador</td>
                                <td>Female</td>
                                <td>Margana</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="footer">
            Copyright 2026 LiteView Corp.
        </div>
    </div>

    <script>
        document.getElementById('refresh-btn').addEventListener('click', function() {
            location.reload();
        });

        function toggleTree(element) {
            // Find the next sibling which should be the tree-children div
            var childrenDiv = element.nextElementSibling;
            if (childrenDiv && childrenDiv.classList.contains('tree-children')) {
                // Toggle display
                var isHidden = childrenDiv.style.display === 'none';
                
                childrenDiv.style.display = isHidden ? 'block' : 'none';
                
                // Toggle icon
                var icon = element.querySelector('.toggle-icon');
                if (icon) {
                    if (isHidden) {
                        icon.classList.remove('fa-plus-square');
                        icon.classList.add('fa-minus-square');
                    } else {
                        icon.classList.remove('fa-minus-square');
                        icon.classList.add('fa-plus-square');
                    }
                }
            }
        }
    </script>
</body>

</html>