海豚监控.py

#!/usr/bin/env python
# -*- coding: utf-8 -*-
 
import requests
#pip install requests
#https://jingyan.baidu.com/article/466506583fec5af549e5f825.html
#进入CMD，然后输入DOS命令进入setup.py文件所在目录，然后输入python setup.py install就搞定了。
from dingtalkchatbot.chatbot import DingtalkChatbot
#https://github.com/zhuifengshen/DingtalkChatbot

#安装winscp
#yum install -y libffi libffi-devel

webhook = 'https://oapi.dingtalk.com/robot/send?access_token=f56aa6136b5fb9640873db8b851291ed6915bf6afbf8e279199991b017ca464a'
#https://oapi.dingtalk.com/robot/send?access_token=f56aa6136b5fb9640873db8b851291ed6915bf6afbf8e279199991b017ca464a
# 初始化机器人小丁
xiaoding = DingtalkChatbot(webhook)
# Text消息@所有人
#xiaoding.send_text(msg='i am dingding robert', is_at_all=True)
url = 'http://www.haitun.hk/'

at_mobiles = ['+86-18576736666']
#xiaoding.send_text(msg='i am dingding robert!', at_mobiles=at_mobiles)
 
def get_server_status(url):
    res = requests.head(url)
    return res.status_code
 
 
if __name__ == '__main__':
    if get_server_status(url) == 200:
        print '%s status is ok' %url
        #xiaoding.send_text(msg='%s status is ok' %url, is_at_all=True)
    else:
        print '%s status is error' %url
        xiaoding.send_text(msg='%s status is error' %url, is_at_all=True)


'''
crontab -e

* * * * * python /tmp/haitun.py

* * * * * sleep 20; python /tmp/haitun.py

* * * * * sleep 40; python /tmp/haitun.py
'''
