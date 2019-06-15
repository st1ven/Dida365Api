# Dida365Api

基于滴答清单的 JSON API 小东西

---

# 1. 配置方式

修改 `ticktick.php` 文件第32行，将 `email` 替换为滴答清单的邮箱，`password` 替换为滴答清单的密码。

---

# 2. 使用方法

举个例子，获取任务组与任务列表：

[https://lab.sangsir.com/api/ticktick.php?action=list](https://lab.sangsir.com/api/ticktick.php?action=list)

```json
{
	"list": {
		"info": "获取任务组与任务列表",
		"method": "GET",
		"params": "action=list"
	},
	"comments": {
		"info": "获取任务清单的评论内容",
		"method": "GET",
		"params": "action=comments&project={projectId}&task={taskId}"
	},
	"completed": {
		"info": "获取已完成的任务清单",
		"method": "GET",
		"params": "action=completed"
	},
	"trash": {
		"info": "获取垃圾箱内的任务清单",
		"method": "GET",
		"params": "action=trash"
	}
}
```
