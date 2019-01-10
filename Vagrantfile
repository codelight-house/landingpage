# -*- mode: ruby -*-
# vi: set ft=ruby :

# Vagrantfile API/syntax version. Don't touch unless you know what you're doing!
VAGRANTFILE_API_VERSION = "2"

Vagrant.configure(VAGRANTFILE_API_VERSION) do |config|
    config.vm.box = "ubuntu/xenial64"
    config.vm.network :private_network, ip: "192.168.56.123"
    config.vm.hostname = "codelighthouse.vagrant"

    config.vm.provision :docker
    config.vm.provision :docker_compose,
        compose_version: "1.18.0"


    config.vm.provider :virtualbox do |vb|
        vb.name = "codelighthouse"
        vb.customize ["modifyvm", :id, "--uartmode1", "disconnected" ]
        vb.customize ["modifyvm", :id, "--memory", "2000" ]
        vb.customize ["modifyvm", :id, "--ostype", "Ubuntu_64" ]
        vb.customize ["modifyvm", :id, "--cpuexecutioncap", "90" ]
        vb.customize ["modifyvm", :id, "--cpus", 1]
        vb.customize ["modifyvm", :id, "--natdnsproxy1", "off"]
    end

    config.vm.provision "shell", privileged: true, inline: <<-shell
        echo "\nVAGRANT ROOT PROVISION SCRIPTS --------------------------------------------\n"
        apt-get update
        # narzedzia dodatkowe dla trybu developerskiego
        apt-get -y install git mc htop
    shell
end