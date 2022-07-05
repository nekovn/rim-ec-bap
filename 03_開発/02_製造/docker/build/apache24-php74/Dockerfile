FROM centos:7.8.2003

# Meta data for image
LABEL name="Web Application Server Image (Apache 2.4 + PHP 7.4)"
LABEL description="The image for web application server based on Apache 2.4 and PHP 7.4."
LABEL maintainer="elseif"
LABEL distribution="centos"
LABEL centos-version="7.8"
LABEL apache-version="2.4"
LABEL php-version="7.4"
LABEL composer-version="1.8.5"


USER root
WORKDIR /tmp

# Provisioning

# - Disable Delta RPMs
RUN yum -y install deltarpm && \
    echo "deltarpm=0" >> /etc/yum.conf

# - Configure environments
RUN localedef -f UTF-8 -i ja_JP ja_JP.UTF-8 && \
    rm -f /etc/localtime && \
    ln -fs /usr/share/zoneinfo/Asia/Tokyo /etc/localtime

ENV container docker
ENV LANG ja_JP.UTF-8
ENV LC_ALL C

# - Enable systemd
RUN (cd /lib/systemd/system/sysinit.target.wants/; for i in *; do [ $i == \
    systemd-tmpfiles-setup.service ] || rm -f $i; done); \
    rm -f /lib/systemd/system/multi-user.target.wants/*;\
    rm -f /etc/systemd/system/*.wants/*;\
    rm -f /lib/systemd/system/local-fs.target.wants/*; \
    rm -f /lib/systemd/system/sockets.target.wants/*udev*; \
    rm -f /lib/systemd/system/sockets.target.wants/*initctl*; \
    rm -f /lib/systemd/system/basic.target.wants/*;\
    rm -f /lib/systemd/system/anaconda.target.wants/*;

# - Add yum repositories
RUN rpm --rebuilddb && \
    yum clean all && \
    yum update -y --exclude=kernel* && \
    yum install -y epel-release && \
    yum -y install http://rpms.famillecollet.com/enterprise/remi-release-7.rpm && \
    yum clean all

# - Install necessary packages
RUN rpm --rebuilddb && \
    yum clean all && \
    yum install -y wget \
    curl \
    which \
    initscripts \
    tar \
    bzip2 \
    bzip2-devel \
    gcc \
    gcc-c++ \
    passwd \
    sudo \
    git \
    subversion \
    autoconf \
    vim \
    openssh-server \
    rsync \
    yum-cron \
    yum-utils \
    rpmdevtools

# Install and configure Apache2
RUN rpm --rebuilddb && \
    yum clean all && \
    yum install -y httpd \
    httpd-devel \
    httpd-tools

# Install and configure PHP
RUN rpm --rebuilddb && \
    yum clean all && \
    yum install -y --enablerepo=remi,remi-php74 \
    php \
    php-fpm \
    php-devel \
    php-xdebug \
    php-cli \
    php-common \
    php-mbstring \
    php-json \
    php-xml \
    php-mysql \
    php-pdo \
    php-gd \
    php-zip \
    php-guzzle-Guzzle

# Install Composer
RUN rpm --rebuilddb; yum install -y --enablerepo=remi,remi-php74 composer \
    mv composer.phar /usr/local/bin/composer

RUN yum install -y php74-php-bcmath

RUN cp /etc/opt/remi/php74/php.d/20-bcmath.ini /etc/php.d/
RUN cp /opt/remi/php74/root/usr/lib64/php/modules/bcmath.so /usr/lib64/php/modules/

# Systemd Configurations
RUN systemctl enable httpd

# Node.js npm install
RUN yum install -y nodejs npm && \
    npm install -g n && \
    n stable && \
    yum remove -y nodejs npm

#postfix install
RUN yum install -y postfix
COPY etc/postfix/main.cf /etc/postfix/main.cf
RUN mkdir -p /etc/skel/Maildir/{new,cur,tmp}
RUN systemctl enable postfix

# Container Default Configurations
USER root
WORKDIR /root

VOLUME [ "/sys/fs/cgroup" ]

CMD ["/usr/sbin/init"]
