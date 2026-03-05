FROM nginx:alpine
# 将当前目录下的所有文件复制到 Nginx 的默认静态资源目录
COPY . /usr/share/nginx/html
