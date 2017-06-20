#/bin/bash
#
# Start test topology with this script
#
echo "|================================================================|"
echo "| USAGE: /opt/viro2/scripts/start_topology.sh <name> |"
echo "|================================================================|"
#
#VARIABLES
#
TOPO_PATH="/opt/viro2/topology"
TOPO_NET_PATH="/opt/viro2/topology/test/net/"
TOPO_NAME=test
#######
mkdir $TOPO_PATH/$TOPO_NAME/saves/$1/
chmod -R 755 $TOPO_PATH/$TOPO_NAME/saves/$1/
#######
echo "CLONING VM"
virsh destroy debian8-test
virt-clone --original debian8-test --name phadac-debian8-test1 --file $TOPO_PATH/$TOPO_NAME/saves/$1/phadac-debian8-test1.img
cp /var/lib/libvirt/images/debian8-test.img $TOPO_PATH/$TOPO_NAME/saves/$1/phadac/phadac-debian8-test1.img
######
echo "CLONING VM"
virt-clone --original debian8-test --name phadac-debian8-test2 --file $TOPO_PATH/$TOPO_NAME/saves/$1/phadac-debian8-test2.img
cp /var/lib/libvirt/images/debian8-test.img $TOPO_PATH/$TOPO_NAME/saves/$1/phadac/phadac-debian8-test2.img

#######

#######
virsh attach-interface --domain phadac-debian8-test1 --type bridge --source deb1ToR1 --model virtio
virsh attach-interface --domain phadac-debian8-test2 --type bridge --source deb2ToR2 --model virtio
#######
