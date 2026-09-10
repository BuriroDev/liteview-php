<?php
$currentTable = $tableName ?? null;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>LiteView - SQLite Web Data Viewer</title>
    <?php $basePath = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\'); ?>
    <link rel="icon" type="image/png" href="<?php echo htmlspecialchars($basePath); ?>/assets/logo.png">
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
            --text-color: #000;
        }

        body.night-mode {
            --bg-color: #1e1e1e;
            --toolbar-bg-top: #2a2a2a;
            --toolbar-bg-bottom: #1a1a1a;
            --panel-border: #444;
            --header-bg: #222;
            --table-header-bg: #333;
            --row-even: #2a2a2a;
            --row-odd: #333333;
            --selection-bg: #555;
            --text-color: #e0e0e0;
        }
        body.night-mode .sidebar { background-color: #252525; color: var(--text-color); }
        body.night-mode .sidebar-tree { background: #252525; }
        body.night-mode .tree-node:hover { background-color: #333; }
        body.night-mode .table-wrapper { background: #2a2a2a; }
        body.night-mode table.data-table th { color: #fff; border-color: #555; }
        body.night-mode table.data-table td { border-color: #555; color: var(--text-color); }
        body.night-mode table.data-table tr:hover { background-color: #444; }
        body.night-mode .content-header { color: var(--text-color); }
        body.night-mode .query-editor { background: #2a2a2a; color: var(--text-color); border-color: #555; }
        body.night-mode .query-results-wrapper { background: #2a2a2a; border-color: #555; }
        body.night-mode .filter-wrapper { background: #252525 !important; border-bottom: 1px solid #555 !important; }
        body.night-mode .filter-input { background: #333; color: #fff; border: 1px solid #555; }
        body.night-mode select { background: #333; color: #fff; border: 1px solid #555; }
        body.night-mode .logo-subtitle { color: #aaa; }
        body.night-mode .footer { color: #aaa; border-top: 1px solid #444; background: #1e1e1e; }
        body.night-mode .tree-node i.fa-database { color: #aaa; }
        body.night-mode .tree-node i.fa-folder { color: #69a3d4; }
        body.night-mode .tree-node i.fa-table { color: #999; }
        body.night-mode .tree-node i.fa-eye { color: #999; }
        body.night-mode .toolbar-area { border-bottom: 1px solid #444; }
        body.night-mode #data-modal > div { background: #2a2a2a !important; color: #e0e0e0; }
        body.night-mode #data-modal > div > div:first-child { background: #222 !important; border-bottom: 1px solid #444 !important; }
        body.night-mode #data-modal > div > div:last-child { background: #222 !important; border-top: 1px solid #444 !important; }
        body.night-mode #modal-content { background: #333; color: #fff; border: 1px solid #555; }

        body {
            margin: 0;
            padding: 0;
            background-color: var(--bg-color);
            color: var(--text-color);
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

        .toolbar-btn i.fa-upload {
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
            max-width: 250px;
            overflow: hidden;
            text-overflow: ellipsis;
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
                <!-- Upload DB Form -->
                <form id="upload-db-form" action="<?php echo htmlspecialchars($_SERVER['SCRIPT_NAME']); ?>/upload" method="POST" enctype="multipart/form-data" style="display: none;">
                    <input type="file" id="sqlite-file-input" name="sqlite_file" accept=".sqlite,.db,.sqlite3" onchange="document.getElementById('upload-db-form').submit();">
                </form>
                
                <div class="toolbar-btn" onclick="document.getElementById('sqlite-file-input').click();">
                    <i class="fa-solid fa-upload"></i>
                    Upload
                </div>
                <div class="toolbar-btn <?php echo (isset($isSqlQueryActive) && $isSqlQueryActive && !isset($isAiQueryActive)) ? 'active' : ''; ?>">
                    <i class="fa-solid fa-table"></i>
                    SQL Query
                </div>
                <div class="toolbar-btn <?php echo (isset($isAiQueryActive) && $isAiQueryActive) ? 'active' : ''; ?>">
                    <i class="fa-solid fa-robot"></i>
                    AI Query
                </div>
                <div class="toolbar-btn">
                    <i class="fa-solid fa-check-square"></i>
                    Select
                </div>
                <div class="toolbar-btn">
                    <i class="fa-solid fa-filter"></i>
                    Filter
                </div>
                <div class="toolbar-btn" id="refresh-btn">
                    <i class="fa-solid fa-sync"></i>
                    Refresh
                </div>
                <div class="toolbar-btn">
                    <i class="fa-solid fa-file-export"></i>
                    Export
                </div>
            </div>

            <div style="margin-left: auto; display: flex; align-items: center;">
                <div id="theme-toggle" style="cursor: pointer; font-size: 18px; color: var(--text-color); padding: 8px;">
                    <i class="fa-solid fa-moon"></i>
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
                            <?php if (isset($tablesList) && !empty($tablesList)): ?>
                                <?php foreach ($tablesList as $table): ?>
                                    <a href="<?php echo htmlspecialchars($_SERVER['SCRIPT_NAME']); ?>/view/<?php echo urlencode($table['name']); ?>">
                                        <div class="tree-node tree-indent-2 <?php echo ($currentTable === $table['name']) ? 'selected' : ''; ?>">
                                            <i class="fa-solid fa-table"></i> <?php echo htmlspecialchars($table['name']); ?>
                                        </div>
                                    </a>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="tree-node tree-indent-2" style="color: #999; font-style: italic;">
                                    Please upload a database using the Upload button.
                                </div>
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
                <div class="filter-wrapper" style="display: none; padding: 6px; background: white; border-bottom: 1px solid var(--panel-border); align-items: center; gap: 6px;">
                    <select class="filter-column" style="padding: 2px;">
                        <?php if (isset($results) && !empty($results)): ?>
                            <?php foreach (array_keys($results[0]) as $columnName): ?>
                                <option value="<?php echo htmlspecialchars($columnName); ?>"><?php echo htmlspecialchars($columnName); ?></option>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <option value="">No columns available</option>
                        <?php endif; ?>
                    </select>
                    <select class="filter-operator" style="padding: 2px;">
                        <option value="=">=</option>
                        <option value="!=">!=</option>
                        <option value=">">&gt;</option>
                        <option value=">=">&gt;=</option>
                        <option value="<">&lt;</option>
                        <option value="<=">&lt;=</option>
                        <option value="LIKE">LIKE</option>
                        <option value="IN">IN</option>
                        <option value="BETWEEN">BETWEEN</option>
                        <option value="IS NULL">IS NULL</option>
                        <option value="IS NOT NULL">IS NOT NULL</option>
                    </select>
                    <input type="text" class="filter-input" placeholder="Filter text..." style="padding: 2px; flex: 1;">
                </div>
                <div class="content-header" style="display: flex; justify-content: space-between; align-items: center;">
                    <span>SQLite Table</span>
                    <button id="copy-selected-btn" style="display: none; padding: 4px 8px; background: #5cb85c; color: white; border: 1px solid #4cae4c; border-radius: 3px; cursor: pointer; font-size: 11px;"><i class="fa-solid fa-copy"></i> Copy Selected</button>
                </div>

                <div class="table-wrapper">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th class="select-col" style="display: none; width: 30px; text-align: center;"><input type="checkbox" id="select-all-cb"></th>
                                <?php if (isset($results) && !empty($results)): ?>
                                    <?php foreach (array_keys($results[0]) as $columnName): ?>
                                        <th><?php echo htmlspecialchars($columnName); ?></th>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <th>No columns available</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (isset($results) && !empty($results)): ?>
                                <?php foreach ($results as $row): ?>
                                    <tr>
                                        <td class="select-col" style="display: none; text-align: center;"><input type="checkbox" class="row-cb"></td>
                                        <?php foreach ($row as $data): 
                                            $fullData = (string)$data;
                                            $strData = $fullData;
                                            // Truncate heavy JSON or massive text fields to 100 chars to prevent browser hangs
                                            if (strlen($strData) > 100) {
                                                $strData = substr($strData, 0, 100) . '... [TRUNCATED]';
                                            }
                                        ?>
                                            <td class="data-cell" data-full="<?php echo htmlspecialchars($fullData, ENT_QUOTES, 'UTF-8'); ?>" title="Double click to view full data" style="cursor: pointer;"><?php echo htmlspecialchars($strData); ?></td>
                                        <?php endforeach; ?>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="100%" style="text-align: center; font-style: italic; color: #666;">No records found</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <?php $showSql = (isset($isSqlQueryActive) && $isSqlQueryActive) || (isset($isAiQueryActive) && $isAiQueryActive); ?>
                <div class="query-editor-wrapper" style="display: <?php echo (isset($isSqlQueryActive) && $isSqlQueryActive && !isset($isAiQueryActive)) ? 'block' : 'none'; ?>;">
                    <form action="<?php echo htmlspecialchars($_SERVER['SCRIPT_NAME']); ?>/sql" method="POST">
                        <textarea class="query-editor" name="query"><?php echo htmlspecialchars($query ?? "SELECT * FROM sqlite_master;"); ?></textarea>
                        <div style="padding: 4px; text-align: right;">
                            <button type="submit" style="padding: 4px 12px; cursor: pointer; background: #5cb85c; color: white; border: 1px solid #4cae4c; border-radius: 3px;">Run Query</button>
                        </div>
                    </form>
                </div>

                <div class="ai-editor-wrapper" style="display: <?php echo (isset($isAiQueryActive) && $isAiQueryActive) ? 'block' : 'none'; ?>; padding: 10px; background: white; border-bottom: 1px solid var(--panel-border);">
                    <form action="<?php echo htmlspecialchars($_SERVER['SCRIPT_NAME']); ?>/ai" method="POST" style="display: flex; gap: 8px; align-items: center;">
                        <input type="text" name="question" placeholder="Ask AI (e.g. show students older than 20)" value="<?php echo htmlspecialchars($userQuestion ?? ''); ?>" style="flex: 1; padding: 6px; font-size: 13px; border: 1px solid #ccc; border-radius: 3px;">
                        <input type="password" name="api_key" placeholder="Gemini API Key" value="<?php echo htmlspecialchars($postedApiKey ?? ''); ?>" style="padding: 6px; width: 250px; font-size: 13px; border: 1px solid #ccc; border-radius: 3px;">
                        <button type="submit" style="padding: 6px 16px; cursor: pointer; background: #9c27b0; color: white; border: 1px solid #7b1fa2; border-radius: 3px; font-weight: bold;"><i class="fa-solid fa-robot"></i> Ask AI</button>
                    </form>
                </div>

                <div class="query-results-wrapper" style="display: <?php echo $showSql ? 'block' : 'none'; ?>;">
                    <?php if (isset($queryError) && $queryError): ?>
                        <div style="color: red; padding: 10px;">Error: <?php echo htmlspecialchars($queryError); ?></div>
                    <?php else: ?>
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <?php if (isset($queryResults) && !empty($queryResults)): ?>
                                        <?php foreach (array_keys($queryResults[0]) as $col): ?>
                                            <th><?php echo htmlspecialchars($col); ?></th>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <th>Result</th>
                                    <?php endif; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (isset($queryResults) && !empty($queryResults)): ?>
                                    <?php foreach ($queryResults as $row): ?>
                                        <tr>
                                            <?php foreach ($row as $data): 
                                                $fullData = (string)$data;
                                                $strData = $fullData;
                                                if (strlen($strData) > 100) {
                                                    $strData = substr($strData, 0, 100) . '... [TRUNCATED]';
                                                }
                                            ?>
                                                <td class="data-cell" data-full="<?php echo htmlspecialchars($fullData, ENT_QUOTES, 'UTF-8'); ?>" title="Double click to view full data" style="cursor: pointer;"><?php echo htmlspecialchars($strData); ?></td>
                                            <?php endforeach; ?>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr><td>No results.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="footer">
            Copyright 2026 LiteView Corp.
        </div>
    </div>

    <div id="data-modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
        <div style="background: white; width: 60%; height: 60%; display: flex; flex-direction: column; border-radius: 6px; box-shadow: 0 4px 12px rgba(0,0,0,0.2);">
            <div style="padding: 10px; border-bottom: 1px solid #ccc; display: flex; justify-content: space-between; align-items: center; background: #f5f5f5; border-radius: 6px 6px 0 0;">
                <h3 style="margin: 0; font-size: 14px;">Cell Data</h3>
                <button id="close-modal-btn" style="cursor: pointer; background: none; border: none; font-size: 16px;"><i class="fa-solid fa-times"></i></button>
            </div>
            <div style="flex: 1; padding: 10px; overflow: hidden; display: flex;">
                <textarea id="modal-content" style="width: 100%; height: 100%; resize: none; border: 1px solid #ccc; padding: 10px; font-family: monospace; font-size: 12px; box-sizing: border-box;" readonly></textarea>
            </div>
            <div style="padding: 10px; border-top: 1px solid #ccc; text-align: right; background: #f5f5f5; border-radius: 0 0 6px 6px;">
                <button id="modal-copy-btn" style="padding: 6px 12px; background: #5bc0de; color: white; border: 1px solid #46b8da; border-radius: 3px; cursor: pointer;"><i class="fa-solid fa-copy"></i> Copy</button>
            </div>
        </div>
    </div>

    <script>
        var themeToggle = document.getElementById('theme-toggle');
        var body = document.body;
        
        // Load theme from localStorage
        if (localStorage.getItem('theme') === 'night') {
            body.classList.add('night-mode');
            themeToggle.innerHTML = '<i class="fa-solid fa-sun"></i>';
        }
        
        themeToggle.addEventListener('click', function() {
            body.classList.toggle('night-mode');
            if (body.classList.contains('night-mode')) {
                localStorage.setItem('theme', 'night');
                themeToggle.innerHTML = '<i class="fa-solid fa-sun"></i>';
            } else {
                localStorage.setItem('theme', 'light');
                themeToggle.innerHTML = '<i class="fa-solid fa-moon"></i>';
            }
        });

        document.getElementById('refresh-btn').addEventListener('click', function() {
            location.reload();
        });

        // Add active state to toolbar buttons when clicked
        document.querySelectorAll('.toolbar-btn').forEach(function(btn) {
            btn.addEventListener('click', function() {
                var isToggleBtn = this.innerText.includes('SQL Query') || this.innerText.includes('AI Query') || this.innerText.includes('Filter') || this.innerText.includes('Select');
                var wasActive = this.classList.contains('active');

                if (isToggleBtn && wasActive) {
                    // Toggle off if clicking an already-active toggle button
                    this.classList.remove('active');
                } else {
                    // Otherwise, activate this button and deactivate others
                    document.querySelectorAll('.toolbar-btn').forEach(function(b) {
                        b.classList.remove('active');
                    });
                    this.classList.add('active');
                }

                // Toggle sections based on which button is active
                var sqlQueryActive = false;
                var aiQueryActive = false;
                var filterActive = false;
                var selectActive = false;
                
                document.querySelectorAll('.toolbar-btn').forEach(function(b) {
                    if (b.classList.contains('active')) {
                        if (b.innerText.includes('SQL Query')) sqlQueryActive = true;
                        if (b.innerText.includes('AI Query')) aiQueryActive = true;
                        if (b.innerText.includes('Filter')) filterActive = true;
                        if (b.innerText.includes('Select')) selectActive = true;
                    }
                });

                var queryEditor = document.querySelector('.query-editor-wrapper');
                var aiEditor = document.querySelector('.ai-editor-wrapper');
                var queryResults = document.querySelector('.query-results-wrapper');
                var filterWrapper = document.querySelector('.filter-wrapper');
                
                if (queryEditor) queryEditor.style.display = sqlQueryActive ? '' : 'none';
                if (aiEditor) aiEditor.style.display = aiQueryActive ? '' : 'none';
                if (queryResults) queryResults.style.display = (sqlQueryActive || aiQueryActive) ? '' : 'none';
                if (filterWrapper) filterWrapper.style.display = filterActive ? 'flex' : 'none';
                
                // Select columns toggle
                document.querySelectorAll('.select-col').forEach(function(col) {
                    col.style.display = selectActive ? '' : 'none';
                });
                
                if (!selectActive) {
                    var selectAllCb = document.getElementById('select-all-cb');
                    if(selectAllCb) selectAllCb.checked = false;
                    document.querySelectorAll('.row-cb').forEach(function(cb) {
                        cb.checked = false;
                    });
                    var copyBtn = document.getElementById('copy-selected-btn');
                    if (copyBtn) copyBtn.style.display = 'none';
                }
            });
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

        // Filter functionality
        var filterInput = document.querySelector('.filter-input');
        var filterColumn = document.querySelector('.filter-column');
        var filterOperator = document.querySelector('.filter-operator');
        var dataTableBody = document.querySelector('.table-wrapper .data-table tbody');

        function applyFilter() {
            if (!dataTableBody) return;
            var rows = dataTableBody.querySelectorAll('tr');
            var colIndex = filterColumn.selectedIndex;
            var op = filterOperator.value;
            var val = filterInput.value.toLowerCase().trim();

            // Toggle input field state based on operator
            if (op === 'IS NULL' || op === 'IS NOT NULL') {
                filterInput.disabled = true;
                filterInput.style.backgroundColor = '#eee';
            } else {
                filterInput.disabled = false;
                filterInput.style.backgroundColor = '';
            }

            rows.forEach(function(row) {
                if (val === '' && op !== 'IS NULL' && op !== 'IS NOT NULL') {
                    row.style.display = '';
                    return;
                }
                var cells = row.querySelectorAll('td:not(.select-col)');
                if (colIndex >= 0 && colIndex < cells.length) {
                    var targetCell = cells[colIndex];
                    var cellVal = (targetCell.hasAttribute('data-full') ? targetCell.getAttribute('data-full') : targetCell.textContent).toLowerCase().trim();
                    var numCellVal = parseFloat(cellVal);
                    var numVal = parseFloat(val);
                    var match = false;
                    
                    switch (op) {
                        case '=':
                            match = cellVal === val;
                            break;
                        case '!=':
                            match = cellVal !== val;
                            break;
                        case '>':
                            if (!isNaN(numCellVal) && !isNaN(numVal)) {
                                match = numCellVal > numVal;
                            } else {
                                match = cellVal > val;
                            }
                            break;
                        case '>=':
                            if (!isNaN(numCellVal) && !isNaN(numVal)) {
                                match = numCellVal >= numVal;
                            } else {
                                match = cellVal >= val;
                            }
                            break;
                        case '<':
                            if (!isNaN(numCellVal) && !isNaN(numVal)) {
                                match = numCellVal < numVal;
                            } else {
                                match = cellVal < val;
                            }
                            break;
                        case '<=':
                            if (!isNaN(numCellVal) && !isNaN(numVal)) {
                                match = numCellVal <= numVal;
                            } else {
                                match = cellVal <= val;
                            }
                            break;
                        case 'LIKE':
                            match = cellVal.includes(val);
                            break;
                        case 'IN':
                            var inVals = val.split(',').map(function(item) { return item.trim(); });
                            match = inVals.includes(cellVal);
                            break;
                        case 'BETWEEN':
                            var betweenVals = val.split(/\s+and\s+|,/i).map(function(item) { return item.trim(); });
                            if (betweenVals.length === 2) {
                                var num1 = parseFloat(betweenVals[0]);
                                var num2 = parseFloat(betweenVals[1]);
                                if (!isNaN(numCellVal) && !isNaN(num1) && !isNaN(num2)) {
                                    match = numCellVal >= num1 && numCellVal <= num2;
                                } else {
                                    match = cellVal >= betweenVals[0] && cellVal <= betweenVals[1];
                                }
                            }
                            break;
                        case 'IS NULL':
                            match = cellVal === '' || cellVal === 'null';
                            break;
                        case 'IS NOT NULL':
                            match = cellVal !== '' && cellVal !== 'null';
                            break;
                    }
                    
                    row.style.display = match ? '' : 'none';
                }
            });
        }

        if (filterInput && filterColumn && filterOperator) {
            filterInput.addEventListener('input', applyFilter);
            filterColumn.addEventListener('change', applyFilter);
            filterOperator.addEventListener('change', applyFilter);
        }

        // Select and Copy logic
        var selectAllCb = document.getElementById('select-all-cb');
        var copySelectedBtn = document.getElementById('copy-selected-btn');
        
        function updateCopyButtonVisibility() {
            var anyChecked = document.querySelector('.row-cb:checked') !== null;
            if (copySelectedBtn) {
                copySelectedBtn.style.display = anyChecked ? 'block' : 'none';
            }
        }

        if (selectAllCb) {
            selectAllCb.addEventListener('change', function() {
                var isChecked = this.checked;
                // Only select visible rows (respecting filter)
                document.querySelectorAll('.table-wrapper .data-table tbody tr').forEach(function(row) {
                    if (row.style.display !== 'none') {
                        var cb = row.querySelector('.row-cb');
                        if (cb) cb.checked = isChecked;
                    }
                });
                updateCopyButtonVisibility();
            });
        }

        document.addEventListener('change', function(e) {
            if (e.target && e.target.classList.contains('row-cb')) {
                updateCopyButtonVisibility();
                // Update select all checkbox state
                if (!e.target.checked && selectAllCb) {
                    selectAllCb.checked = false;
                }
            }
        });

        if (copySelectedBtn) {
            copySelectedBtn.addEventListener('click', function() {
                var table = document.querySelector('.table-wrapper .data-table');
                if (!table) return;
                
                var headers = [];
                var headerCells = table.querySelectorAll('thead th:not(.select-col)');
                headerCells.forEach(function(th) {
                    headers.push(th.innerText.trim());
                });

                var dataToCopy = headers.join('\t') + '\n';
                
                var rows = table.querySelectorAll('tbody tr');
                rows.forEach(function(row) {
                    var cb = row.querySelector('.row-cb');
                    if (cb && cb.checked && row.style.display !== 'none') {
                        var rowData = [];
                        var cells = row.querySelectorAll('td:not(.select-col)');
                        cells.forEach(function(td) {
                            var text = td.hasAttribute('data-full') ? td.getAttribute('data-full') : td.innerText.trim();
                            rowData.push(text);
                        });
                        dataToCopy += rowData.join('\t') + '\n';
                    }
                });

                navigator.clipboard.writeText(dataToCopy).then(function() {
                    var originalText = copySelectedBtn.innerHTML;
                    copySelectedBtn.innerHTML = '<i class="fa-solid fa-check"></i> Copied!';
                    setTimeout(function() {
                        copySelectedBtn.innerHTML = originalText;
                    }, 2000);
                }).catch(function(err) {
                    console.error('Failed to copy: ', err);
                });
            });
        }

        // Modal Logic
        var dataModal = document.getElementById('data-modal');
        var modalContent = document.getElementById('modal-content');
        var closeModalBtn = document.getElementById('close-modal-btn');
        var modalCopyBtn = document.getElementById('modal-copy-btn');

        document.addEventListener('dblclick', function(e) {
            var td = e.target.closest('td.data-cell');
            if (td) {
                var fullData = td.hasAttribute('data-full') ? td.getAttribute('data-full') : td.innerText;
                modalContent.value = fullData;
                dataModal.style.display = 'flex';
            }
        });

        if (closeModalBtn) {
            closeModalBtn.addEventListener('click', function() {
                dataModal.style.display = 'none';
            });
        }
        
        if (dataModal) {
            dataModal.addEventListener('click', function(e) {
                if (e.target === dataModal) {
                    dataModal.style.display = 'none';
                }
            });
        }

        if (modalCopyBtn) {
            modalCopyBtn.addEventListener('click', function() {
                navigator.clipboard.writeText(modalContent.value).then(function() {
                    var original = modalCopyBtn.innerHTML;
                    modalCopyBtn.innerHTML = '<i class="fa-solid fa-check"></i> Copied!';
                    setTimeout(function() {
                        modalCopyBtn.innerHTML = original;
                    }, 2000);
                }).catch(function(err) {
                    console.error('Failed to copy modal content: ', err);
                });
            });
        }
    </script>
</body>

</html>