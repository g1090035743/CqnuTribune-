<?php 
		/* 
			扩展：
			本来打算我是用框架来写这个项目，但是由于不是一个人，所以我只
			用源码来写这个项目，所以不存在session卡死的情况。
			如果是用框架的话，比如laravel。多个ajax并发程序经过中间件
			判断session用户的时候就会出现session卡死状态，导致程序崩溃这时
			候需要用session_write_close()这个函数来解除session卡死
 		*/
		//=====轮询长连接开始=====
        set_time_limit(0);
		//设置响应时间为无限(因为php动态脚本默认支持执行30秒，超过30秒就会报错提醒响应时间超过30秒)
        $redis = new Redis();    
		//实例化Redis数据库
		$redis->pconnect("localhost","6379");  
		//长连接redis(这里的长连接是缓存服务器redis长连接不是ajax长连接)
		/*
		为什么使用长连接：
		因为PHP脚本运行结束后，redis连接资源并没有释放，而是由php-fpm进程保持
		(可以通过 kill php-fpm看到，当脚本停止运行后连接释放 php的脚本运行结束和
		停止运行是两个概念，运行结束后一段时间内才会停止运行)所以使用pconnect代替connect，可以减少频繁建立redis连接的消耗。而且
		在一个进程中，pconnect是可以保持redis连接状态提供复用的。
		*/
		$redis->select(1);
		/*
		选择1号数据库(因为redis本身就是一个数据库，主要用于集群
		和分布式系统，我用它来当缓存服务器有点大材小用)
		*/
		$sender = $_POST['sender'];
		// 获取当前用户
        $getter = $_POST['getter'];
        // 获取对方用户
        $key = $getter."to".$sender;
        /* 
        	拼接字符串 比如当前用户是 张三 对方用户是 李四
			那么拼接起来的key就是 '李四to张三'  因为要获取
			的是对方发过来的消息。
        */
        // =====ajax发送请求长连接开始=====
        while (true) {
		   if ($redis->exists($key)) {
				$value =  $redis->get($key);
				// 如果这个key存在就得到这个key里面的即时消息
				$redis->delete($key);
				// 获取到了即时消息就把这个key删掉
				$redis->close();
				// 关闭reids连接，以免多人使用，导致并发ajax程序的reids资源消耗过多
				$value = explode("&@part",$value);
				/* 
					然后分离消息和时间存到value数组里面
					(这时候value是数组，为什么从新声明变
					量，因为用户多了，你没多声明一个变量
					，php就会分配一个变量空间，浪费服务器
					资源，所以在大型项目中，能少用变量就少用)
				*/
				$value = json_encode($value);
				// 解析成json格式字符串
				exit($value);
	  			/*
	  			 退出脚本并返回数据 注意：这里一定要用exit
				 虽然echo也行。exit是停止脚本运行，而如果是
				 echo 的话也会返回到ajax客户端，但是如果下面
				 还有语句，会继续执行，知道脚本停止。上面说过
				 结束php程序之后过一段时间才会停止脚本运行
				*/

			}
			    usleep(5000);
	            /*
	            每隔1/1000秒执行一次
	            usleep 是隔多少毫秒在执行下面的程序
	            如果不写这句话，那么程序一直跑，如果
	            这时候没有数据写进redis缓存服务器，时
	            间久了，服务器就会boom~ 爆炸！
	            */
            }
 		//=====ajax发送请求长连接结束=====
 ?>