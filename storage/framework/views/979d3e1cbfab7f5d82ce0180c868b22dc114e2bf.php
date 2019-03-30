 
<?php $__env->startSection('title', '後臺'); ?>

<?php $__env->startSection('content'); ?>

<!-- 左側邊欄 -->
<div class="ts left vertical fluid inverted visible menu sidebar">
    
    <!-- 個人資料項目 -->
    <div class="center aligned item">
        <img class="ts tiny circular image" src="https://i.imgur.com/jpy2fq6.jpg">
        <br />
        <br />
        <div>奇哥</div>
    </div>
    <!-- / 個人資料項目 -->

    <div class="item">
        <div class="menu">
            <a class="item center-aligned" id="backup">備份設定</a>
        </div>
    </div>

    <!-- 底部選單 -->
    <div class="bottom menu">
        <!-- 登出 -->
        <a href="/pineapple/logout" class="item">
            <i class="sign out icon light-icon"></i>
            登出
        </a>
        <!-- / 登出 -->
    </div>
    <!-- / 底部選單 -->
</div>
<!-- / 左側邊欄 -->

<!-- 可擠壓式的推動容器 -->
<div class="squeezable pusher">
    <br />

    <!-- 主要容器 -->
    <div class="ts container">
        <div class="ts relaxed grid">
            <!-- 標題欄位 -->
            <div class="sixteen wide column">
                <h3 class="ts header">
                    預約次數
                    <div class="sub header">從這裡快速檢視預約流量。</div>
                </h3>
            </div>
            <!-- / 標題欄位 -->

            <!-- 大略卡片欄位 -->
            <div class="sixteen wide column">
                <div class="ts three cards">
                    <!-- 本月拜訪次數 -->
                    <div class="ts card">
                        <div class="content">
                            <!-- 統計數據 -->
                            <div class="ts left aligned statistic">
                                <div class="value" id="total"></div>
                                <div class="label">已經預約次數</div>
                            </div>
                            <!-- / 統計數據 -->
                        </div>
                        <div class="symbol">
                            <i class="archive icon"></i>
                        </div>
                    </div>
                    <!-- / 本月拜訪次數 -->

                    <!-- 總會員數 -->
                    <div class="ts card">
                        <div class="content">
                            <!-- 統計數據 -->
                            <div class="ts left aligned statistic">
                                <div class="value" id="book"></div>
                                <div class="label">預約中數量</div>
                            </div>
                            <!-- / 統計數據 -->
                        </div>
                        <div class="symbol">
                            <i class="time icon"></i>
                        </div>
                    </div>
                    <!-- / 總會員數 -->

                    <!-- 平均在線分鐘數 -->
                    <div class="ts card">
                        <div class="content">
                            <!-- 統計數據 -->
                            <div class="ts left aligned statistic">
                                <div class="value" id="members"></div>
                                <div class="label">使用者數量</div>
                            </div>
                            <!-- / 統計數據 -->
                        </div>
                        <div class="symbol">
                            <i class="users icon"></i>
                        </div>
                    </div>
                    <!-- / 平均在線分鐘數 -->
                </div>

                <!-- 區隔線 -->
                <div class="ts section divider"></div>
                <!-- / 區隔線 -->
            </div>
            <!-- / 大略卡片欄位 -->

            <!-- 左側雜項欄位 -->
            <div class="sixteen wide column">
                <button class="ts labeled info icon button" id="addBook"><i class="plus inverted icon"></i>一般預約</button>
                <button class="ts labeled info icon button" id="addBookBatch"><i class="calendar inverted icon"></i>批次預約</button>
                <button class="ts labeled info icon button" id="print"><i class="print inverted icon"></i>列印</button>
                <br />
                <div class="ts top attached info segment">
                    <div class="ts large header">預約中列表</div>
                </div>
                <div class="ts attached segment">
                    <table class="ts very basic table row-border" id="myTable">
                        <thead>
                            <tr>
                                <th>租借人</th>
                                <th>學號</th>
                                <th>原因</th>
                                <th class="hidden">描述</th>
                                <th>聯絡電話</th>
                                <th>教室</th>
                                <th>租借日</th>
                                <th>期間</th>
                                <th class="hidden">建立時間</th>
                                <th class="hidden">操作</th>
                            </tr>
                        </thead>
                        <tbody id="rental"></tbody>
                    </table>
                </div>
            </div>
            <!-- / 左側雜項欄位 -->
        </div>
    </div>
    <!-- / 主要容器 -->
</div>
<!-- / 可擠壓式的推動容器 -->

<div class="ts modals dimmer">
    <dialog id="settingModal" class="ts modal closable">
        <div class="header">備份收件者設定</div>
        <div class="content">
            <form class="ts relaxed form">
                <h1 class="ts header center aligned">新增收件者</h1>
                <div class="ts divider"></div>
                <div class="fields">
                    <div class="basic field">
                        <label>Email</label>
                        <input type="text" id="recipient-email">
                    </div>
                    <div class="basic field">
                        <label>稱呼</label>
                        <input type="text" id="recipient-name">
                    </div>
                </div>
                <h1 class="ts header center aligned">目前收件者</h1>
                <div class="ts divider"></div>
                <div class="ts list" id="recipientLists"></div>
                <div class="ts divider"></div>
                <h1 class="ts header center aligned">操作</h1>
                <button class="ts info button" id="backupTest">
                    手動備份
                </button>
            </form>
        </div>
        <div class="actions">
            <button class="ts deny button">
                關閉
            </button>
            <button class="ts positive button">
                新增
            </button>
        </div>
    </dialog>
</div>

<div class="ts modals dimmer">
    <dialog id="optionModal" class="ts modal closable">
        <div class="header" id="edit-name"></div>
        <div class="content">
            <form class="ts relaxed form" style="display:none;" id="edit-container">
                <div class="fields">
                    <div class="basic field">
                        <label>租借原因</label>
                        <input type="text" id="edit-title">
                    </div>
                    <div class="basic field">
                        <label>電話</label>
                        <input type="text" id="edit-phone">
                    </div>
                </div>
                <div class="basic field">
                    <label>描述</label>
                    <input type="text" id="edit-desc">
                </div>
                <br />
                <div class="fields">
                    <div class="basic field">
                        <label>開始</label>
                        <input type="time" id="edit-start">
                        <small>* 分鐘只能設置 00 或 30</small>
                    </div>
                    <div class="basic field">
                        <label>結束</label>
                        <input type="time" id="edit-end">
                        <small>* 分鐘只能設置 00 或 30</small>
                    </div>
                </div>
            </form>
        </div>
        <div class="actions">
            <button class="ts deny button">
                不了
            </button>
            <button class="ts positive button">
                改變
            </button>
        </div>
    </dialog>
</div>

<div class="ts modals dimmer">
    <dialog id="addBookModal" class="ts modal closable">
        <div class="header" id="edit-name"></div>
        <div class="content">
            <form class="ts relaxed form">
                <div class="field">
                    <label>預約教室</label>
                    <select id="room">
                        <option value="102">102</option>
                        <option value="103">103</option>
                        <option value="104">104</option>
                        <option value="203">203</option>
                        <option value="205">205</option>
                    </select>
                </div>
                <div class="fields">
                    <div class="basic field">
                        <label>租借人</label>
                        <input type="text" id="add-name" required>
                    </div>
                    <div class="basic field">
                        <label>學號</label>
                        <input type="text" id="add-username" placeholder="只能是數字" required>
                    </div>
                </div>
                <div class="fields">
                    <div class="basic field">
                        <label>原因</label>
                        <input type="text" id="add-title" required>
                    </div>
                    <div class="basic field">
                        <label>聯絡電話</label>
                        <input type="text" id="add-phone" required>
                    </div>
                </div>
                <div class="fields">
                    <div class="basic field">
                        <label>描述</label>
                        <input type="text" id="add-desc">
                    </div>
                </div>
                <br />
                <div class="fields">
                    <div class="basic field">
                        <label>日期</label>
                        <input type="date" id="rentDate" value="2013-01-08" required>
                        <small>* 預設為今日</small>
                    </div>
                    <div class="basic field">
                        <label>開始</label>
                        <input type="time" id="start" required>
                        <small>* 分鐘只能設置 00 或 30</small>
                    </div>
                    <div class="basic field">
                        <label>結束</label>
                        <input type="time" id="end" required>
                        <small>* 分鐘只能設置 00 或 30</small>
                    </div>
                </div>
            </form>
        </div>
        <div class="actions">
            <button class="ts deny button">
                取消
            </button>
            <button class="ts positive button">
                預約
            </button>
        </div>
    </dialog>
</div>

<div class="ts modals dimmer">
    <dialog id="addBookBatchModal" class="ts modal closable">
        <div class="content">
            <form class="ts relaxed form">
                <div class="field">
                    <label>預約教室</label>
                    <select id="multip-room">
                        <option value="102">102</option>
                        <option value="103">103</option>
                        <option value="104">104</option>
                        <option value="203">203</option>
                        <option value="205">205</option>
                    </select>
                </div>
                <div class="fields">
                    <div class="basic field">
                        <label>租借人</label>
                        <input type="text" id="multip-add-name" required>
                    </div>
                    <div class="basic field">
                        <label>學號</label>
                        <input type="text" id="multip-add-username" placeholder="只能是數字" required>
                    </div>
                </div>
                <div class="fields">
                    <div class="basic field">
                        <label>原因</label>
                        <input type="text" id="multip-add-title" required>
                    </div>
                    <div class="basic field">
                        <label>聯絡電話</label>
                        <input type="text" id="multip-add-phone" required>
                    </div>
                </div>
                <div class="fields">
                    <div class="basic field">
                        <label>描述</label>
                        <input type="text" id="multip-add-desc">
                    </div>
                </div>
                <br />
                <div class="fields">
                    <div class="field">
                        <label>日期</label>
                        <input id="batch-date">
                    </div>
                    <div class="basic field">
                        <label>開始</label>
                        <input type="time" id="multip-start" required>
                        <small>* 分鐘只能設置 00 或 30</small>
                    </div>
                    <div class="basic field">
                        <label>結束</label>
                        <input type="time" id="multip-end" required>
                        <small>* 分鐘只能設置 00 或 30</small>
                    </div>
                </div>
            </form>
        </div>
        <div class="actions">
            <button class="ts deny button">
                取消
            </button>
            <button class="ts positive button">
                預約
            </button>
        </div>
    </dialog>
</div>

<div class="ts modals dimmer">
    <dialog class="ts fullscreen closable modal" id="conflictModal">
        <div class="header" id="selectorConflict">
            選取的時段已被預約
        </div>
        <div class="content">
            <table class="ts borderless table">
                <thead>
                    <tr>
                    <th>#</th>
                    <th>學生</th>
                    <th>學號</th>
                    <th>手機</th>
                    <th>租借日</th>
                    <th>期間</th>
                    <th>原因</th>
                </tr>
            </thead>
            <tbody id="conflict"></tbody>
            <caption id="conflictInfo"></caption>
        </div>
    </dialog>
</div>

<div class="ts bottom right snackbar">
    <div class="content"></div>
    <a class="action"></a>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.adminLayout', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>