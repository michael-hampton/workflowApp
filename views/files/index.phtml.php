
<link rel="stylesheet" media="only screen and (max-width: 400px)" href="/FormBuilder/public/css/fileman/fileman_mobile.css" />
<link rel="stylesheet" media="only screen and (min-width: 401px)" href="/FormBuilder/public/css/fileman/fileman.css" />
<script type="text/javascript" src="/FormBuilder/public/js/fileman/jquery_ui_dateformat.js"></script>


<table cellpadding="0" cellspacing="0" id="fileman">
    <tr>
        <td valign="top" class="pnlDirs" id="dirActions">
            <div class="actions">
                <span id="btncreateDir" title="Create new directory" data-lang-v="CreateDir" data-lang-t="T_CreateDir">Create</span>
                <span id="btnRenameDir" title="Rename directory" data-lang-v="RenameDir" data-lang-t="T_RenameDir">Rename</span>
                <span id="btnDeleteDir" title="Delete selected directory" data-lang-v="DeleteDir" data-lang-t="T_DeleteDir">Delete</span>
                <span id="btnDownloadDir" title="Download selected directory" data-lang-v="Download" data-lang-t="T_DownloadDir">Download</span>
            </div>
            <h4 id="pnlLoadingDirs">Loading directories...</h4>
            <div class="scrollPane"><ul id="pnlDirList"></ul></div>
        </td>
        <td valign="top" id="fileActions">
            <input type="hidden" id="hdViewType" value="list">
            <input type="hidden" id="hdOrder" value="asc">
            <div class="actions">
                <input type="button" id="btnAddFile" value="Add file" title="Upload files" data-lang-v="AddFile" data-lang-t="T_AddFile" />
                <input type="button" id="btnPreviewFile" value="Preview" title="Preview selected file" data-lang-v="Preview" data-lang-t="T_Preview" />
                <input type="button" id="btnRenameFile" value="Rename" title="Rename selected file" data-lang-v="RenameFile" data-lang-t="T_RenameFile" />
                <input type="button" id="btnEditFile" value="Edit file" title="Edit file" data-lang-v="editFile" data-lang-t="T_EditFile" />
                <input type="button" id="btnDownloadFile" value="Download" title="Download selected file" data-lang-v="DownloadFile" data-lang-t="T_DownloadFile" />
                <input type="button" id="btnDeleteFile" value="Delete" title="Delete selected file" data-lang-v="DeleteFile" data-lang-t="T_DeleteFile" />
                <div id="btnFile2">
                    <strong data-lang="OrderBy">Order by:</strong>
                    <select id="ddlOrder">
                        <option value="name_asc" data-lang="Name_asc">&uarr;&nbsp;&nbsp;Name</option>
                        <option value="size_asc" data-lang="Size_asc">&uarr;&nbsp;&nbsp;Size</option>
                        <option value="time_asc" data-lang="Date_asc">&uarr;&nbsp;&nbsp;Date</option>
                        <option value="name_desc" data-lang="Name_desc">&darr;&nbsp;&nbsp;Name</option>
                        <option value="size_desc" data-lang="Size_desc">&darr;&nbsp;&nbsp;Size</option>
                        <option value="time_desc" data-lang="Date_desc">&darr;&nbsp;&nbsp;Date</option>
                    </select>&nbsp;&nbsp;
                    <input type="button" id="btnListView" class="btnView" value=" " title="List view" data-lang-t="T_ListView" />
                    <input type="button" id="btnThumbView" class="btnView" value=" " title="Thumbnails view" data-lang-t="T_ThumbsView" />&nbsp;&nbsp;
                    <input type="text" id="txtSearch" />
                </div>
                <br class="clr">
            </div>
            <div class="scrollPane">
                <h4 id="pnlLoading" data-lang="LoadingFiles">Loading files...</h4>
                <div id="pnlEmptyDir" data-lang="DirIsEmpty">This folder is empty</div>
                <div id="pnlSearchNoFiles" data-lang="NoFilesFound">No files found</div>
                <ul id="pnlFileList"></ul>
            </div>
        </td>
    </tr>
    <tr>
        <td class="bottomLine"> &bull; <a href="http://coursesweb.net/" target="_blank">CoursesWeb.net</a> </td>
        <td class="bottomLine"><div id="pnlStatus">Status bar</div></td>
    </tr>
</table>

<iframe name="frmUploadFile" width="0" height="0" style="display:none;border:0;"></iframe>
<div id="dlgAddFile">
    <form action="#" name="addfile" id="frmUpload" method="post" target="frmUploadFile" enctype="multipart/form-data">
        <input type="hidden" name="d" id="hdDir" />
        <div class="form">
            Select files to upload<br />
            <input type="file" name="files[]" id="fileUploads" multiple="multiple" />
        </div>
    </form>
</div>
<div id="menuDir" class="contextMenu">
    <span data-lang="Download" id="mnuDownloadDir">Download</span>
    <span data-lang="T_CreateDir" id="mnuCreateDir">Create new</span>
    <span data-lang="Cut" id="mnuDirCut">Cut</span>
    <span data-lang="Copy" id="mnuDirCopy">Copy</span>
    <span data-lang="Paste" class="paste pale" id="mnuDirPaste">Paste<br><em class="paste_elm"></em></span>
    <span data-lang="RenameDir" id="mnuRenameDir">Rename</span>
    <span data-lang="DeleteDir" id="mnuDeleteDir">Delete</span>
</div>
<div id="menuFile" class="contextMenu">
    <span data-lang="Preview" id="mnuPreview">Preview</span>
    <span data-lang="DownloadFile" id="mnuDownload">Download</span>
    <span data-lang="Cut" id="mnuFileCut">Cut</span>
    <span data-lang="Copy" id="mnuFileCopy">Copy</span>
    <span data-lang="Paste" class="paste pale" id="mnuFilePaste">Paste<br><em class="paste_elm"></em></span>
    <span data-lang="RenameFile" id="mnuRenameFile">Rename</span>
    <span data-lang="editFile" id="mnuEditFile">Edit</span>
    <span data-lang="DeleteFile" id="mnuDeleteFile">Delete</span>
</div>
<div id="pnlRenameFile"><input type="text" id="txtFileName"></div>
<div id="pnlDirName"><input type="text" id="txtDirName"></div>
<div id="f_editf">
    <form action="#" method="post" target="frmUploadFile" id="frm_edit">
        <input type="hidden" name="f" id="f_ed_f" />
        <textarea id="fc" name="fc"></textarea>
        <input type="submit" id="btnSendCnt" value="Send" /><button id="cnc_edit">Cancel</button>
    </form>
</div>

<script type="text/javascript" src="/FormBuilder/public/js/fileman/fileman_min.js"></script>