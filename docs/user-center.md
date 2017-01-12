## 用户中心设计

目的: 统一所有项目的登录注册, 实现账号体系的互通.

原因: 目前工作主要集中在IM+新功能的开发, 涉及用户模块/联系人模块/消息发送模块三个,
总体的开发量如果按照之前的开发模式, 能够由两个开发在两个工作日内完成.

所以可以在这段时间, 完成一个简单的过渡设计.

保留抽象的服务接口, 后期再持续做优化.

## 方案提议

1. 依然使用Lumen框架实现用户中心, 因为我们现在几乎所有API都由Lumen实现, 实现的风险及时间代价最小,

并且能够保证承受合理的负载.

2. 通过HTTP接口调用用户中心.

3. 验证使用最简单的app_id + app_secret的方式, 添加app_id是因为可能后面还有其他的项目会调用用户中心.

4. 请求及响应的格式同FameServerAPI服务端, 保持一致性.

5. 用户中心的实现, 类似一个简单的后端API项目.