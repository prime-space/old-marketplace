<div class="HelpListF">
    <div class="HelpListT">Задать вопрос администратору:</div>
    <form class="HelpListForm" onsubmit="return false;">
        <div class="HelpListTt">Тема:</div>
        <input type="text" maxlength="100" id="user_helpNewTitle" />
        <div class="HelpListTt">Сообщение:</div>
        <textarea id="user_helpNewText" maxlength="1500"></textarea>
        <div id="user_helpNewSubmitDiv">
            <input class="btn btn-small btn-success" type="submit" value="Отправить" onclick="user_helpNew();" />
        </div>
    </form>

    <div class="HelpListT">Ваши обращения:</div>

    <div class="HelpListH">
        <div class="HelpListH_i">id</div>
        <div class="HelpListH_t">Тема</div>
        <div class="HelpListH_d">Дата</div>
        <div class="clr"></div>
    </div>

    <div id="userOrAdmin_helpList"></div>

</div>